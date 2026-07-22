<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\CashOpname;
use App\Http\Requests\CreateCashOpnameRequest;
use App\Http\Requests\UpdateCashOpnameRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;


class CashOpnameController extends Controller
{
        use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-cash_opname')->only(['index', 'show']);
        $this->middleware('permission:create-cash_opname')->only(['create', 'store']);
        $this->middleware('permission:update-cash_opname')->only(['edit', 'update']);
        $this->middleware('permission:delete-cash_opname')->only('destroy');
    }

    public function index()
    {
        return view('admin.cash_opnames.index');
    }

    public function create()
    {
        $cashOpname = new CashOpname();
        $fileUrls = $this->getFileUrls(CashOpname::class);
        $users = \App\Models\User::pluck('name', 'id');

        $today = \Carbon\Carbon::today();
        $user_id = auth()->id();
        
        // Calculate expected values based on today's orders
        $orders = \App\Models\Order::where('user_id', $user_id)
                    ->whereDate('created_at', $today)
                    ->where('payment_status', 'paid')
                    ->get();
                    
        $cashOpname->user_id = $user_id;
        $cashOpname->opname_date = $today->format('Y-m-d');
        $cashOpname->expected_cash = $orders->where('payment_method', 'cash')->sum('total');
        $cashOpname->expected_qris = $orders->where('payment_method', 'qris')->sum('total');
        $cashOpname->expected_transfer = $orders->where('payment_method', 'transfer')->sum('total');

        return view('admin.cash_opnames.create', compact('cashOpname', 'fileUrls', 'users'));
    }

    public function store(CreateCashOpnameRequest $request)
    {
        \Log::info('CashOpname Store Request Data:', $request->all());
        $data = $request->validated();
        
        // Remove commas from autoNumeric inputs
        $data['expected_cash'] = (int) str_replace(',', '', $data['expected_cash'] ?? 0);
        $data['actual_cash'] = (int) str_replace(',', '', $data['actual_cash'] ?? 0);
        $data['expected_qris'] = (int) str_replace(',', '', $data['expected_qris'] ?? 0);
        $data['actual_qris'] = (int) str_replace(',', '', $data['actual_qris'] ?? 0);
        $data['expected_transfer'] = (int) str_replace(',', '', $data['expected_transfer'] ?? 0);
        $data['actual_transfer'] = (int) str_replace(',', '', $data['actual_transfer'] ?? 0);

        // Calculate differences
        $expectedTotal = $data['expected_cash'] + $data['expected_qris'] + $data['expected_transfer'];
        $actualTotal = $data['actual_cash'] + $data['actual_qris'] + $data['actual_transfer'];
        $data['difference'] = $actualTotal - $expectedTotal;
        
        if ($data['difference'] == 0) {
            $data['status'] = 'matched';
        } elseif ($data['difference'] > 0) {
            $data['status'] = 'overage';
        } else {
            $data['status'] = 'shortage';
        }

        $this->handleFileUploads($request, $data, CashOpname::class, 'cash_opname');

        $cashOpname = CashOpname::create($data);

        ActivityLogService::logCreate($cashOpname);

        if (auth()->user()->can('view-cash_opname')) {
            return redirect()->route('admin.cash_opnames.index')
                ->with('success', 'CashOpname created successfully.');
        } else {
            return redirect()->route('admin.dashboard')
                ->with('success', 'CashOpname created successfully.');
        }
    }

    public function show(CashOpname $cashOpname)
    {
        return view('admin.cash_opnames.show', compact('cashOpname'));
    }

    public function edit(CashOpname $cashOpname)
    {
        $fileUrls = $this->getFileUrls(CashOpname::class, $cashOpname);
        $users = \App\Models\User::pluck('name', 'id');

        return view('admin.cash_opnames.edit', compact('cashOpname', 'fileUrls', 'users'));
    }

    public function update(UpdateCashOpnameRequest $request, CashOpname $cashOpname)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, CashOpname::class, 'cash_opname', $cashOpname);

        $oldValues = $cashOpname->getOriginal();

        $cashOpname->update($data);

        ActivityLogService::logUpdate($cashOpname, $oldValues);

        return redirect()->route('admin.cash_opnames.index')->with('success', 'CashOpname updated successfully.');
    }

    public function destroy(CashOpname $cashOpname)
    {
        $this->deleteAssociatedFiles(CashOpname::class, $cashOpname);

        ActivityLogService::logDelete($cashOpname);

        $cashOpname->delete();
        return redirect()->route('admin.cash_opnames.index')->with('success', 'CashOpname deleted successfully.');
    }


}
