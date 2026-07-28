<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\DiningTable;
use App\Http\Requests\CreateDiningTableRequest;
use App\Http\Requests\UpdateDiningTableRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;


class DiningTableController extends Controller
{
        use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-dining_tables')->only(['index', 'show']);
        $this->middleware('permission:create-dining_table')->only(['create', 'store']);
        $this->middleware('permission:edit-dining_table')->only(['edit', 'update']);
        $this->middleware('permission:delete-dining_table')->only('destroy');
    }

    public function index()
    {
        return view('admin.dining_tables.index');
    }

    public function create()
    {
        $diningTable = new DiningTable();
        $fileUrls = $this->getFileUrls(DiningTable::class);

        return view('admin.dining_tables.create', compact('diningTable', 'fileUrls'));
    }

    public function store(CreateDiningTableRequest $request)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, DiningTable::class, 'dining_table');

        $diningTable = DiningTable::create($data);

        $diningTable->update([
            'qr_code' => route('customer.menu', ['table' => $diningTable->id])
        ]);

        ActivityLogService::logCreate($diningTable);

        return redirect()->route('admin.dining_tables.index')->with('success', 'Dining Table created successfully.');
    }

    public function show(DiningTable $diningTable)
    {
        return view('admin.dining_tables.show', compact('diningTable'));
    }

    public function edit(DiningTable $diningTable)
    {
        $fileUrls = $this->getFileUrls(DiningTable::class, $diningTable);

        return view('admin.dining_tables.edit', compact('diningTable', 'fileUrls'));
    }

    public function update(UpdateDiningTableRequest $request, DiningTable $diningTable)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, DiningTable::class, 'dining_table', $diningTable);

        $oldValues = $diningTable->getOriginal();

        $diningTable->update($data);

        ActivityLogService::logUpdate($diningTable, $oldValues);

        return redirect()->route('admin.dining_tables.index')->with('success', 'Dining Table updated successfully.');
    }

    public function destroy(DiningTable $diningTable)
    {
        $this->deleteAssociatedFiles(DiningTable::class, $diningTable);

        ActivityLogService::logDelete($diningTable);

        $diningTable->delete();
        return redirect()->route('admin.dining_tables.index')->with('success', 'Dining Table deleted successfully.');
    }

    public function printQr(DiningTable $diningTable)
    {
        return view('admin.dining_tables.print-qr', compact('diningTable'));
    }

    public function printAllQr(\Illuminate\Http\Request $request)
    {
        $query = DiningTable::orderBy('number');
        
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids);
        }

        $diningTables = $query->get();
        return view('admin.dining_tables.print-all-qr', compact('diningTables'));
    }

}
