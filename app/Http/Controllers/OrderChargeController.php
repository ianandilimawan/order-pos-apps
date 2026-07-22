<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\OrderCharge;
use App\Http\Requests\CreateOrderChargeRequest;
use App\Http\Requests\UpdateOrderChargeRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;


class OrderChargeController extends Controller
{
        use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-order_charges')->only(['index', 'show']);
        $this->middleware('permission:create-order_charge')->only(['create', 'store']);
        $this->middleware('permission:edit-order_charge')->only(['edit', 'update']);
        $this->middleware('permission:delete-order_charge')->only('destroy');
    }

    public function index()
    {
        return view('admin.order_charges.index');
    }

    public function create()
    {
        $orderCharge = new OrderCharge();
        $fileUrls = $this->getFileUrls(OrderCharge::class);
        $orders = \App\Models\Order::pluck('order_number', 'id');
        $chargeSettings = \App\Models\ChargeSetting::pluck('name', 'id');

        return view('admin.order_charges.create', compact('orderCharge', 'fileUrls', 'orders', 'chargeSettings'));
    }

    public function store(CreateOrderChargeRequest $request)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, OrderCharge::class, 'order_charge');

        $orderCharge = OrderCharge::create($data);

        ActivityLogService::logCreate($orderCharge);

        return redirect()->route('admin.order_charges.index')->with('success', 'OrderCharge created successfully.');
    }

    public function show(OrderCharge $orderCharge)
    {
        return view('admin.order_charges.show', compact('orderCharge'));
    }

    public function edit(OrderCharge $orderCharge)
    {
        $fileUrls = $this->getFileUrls(OrderCharge::class, $orderCharge);
        $orders = \App\Models\Order::pluck('order_number', 'id');
        $chargeSettings = \App\Models\ChargeSetting::pluck('name', 'id');

        return view('admin.order_charges.edit', compact('orderCharge', 'fileUrls', 'orders', 'chargeSettings'));
    }

    public function update(UpdateOrderChargeRequest $request, OrderCharge $orderCharge)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, OrderCharge::class, 'order_charge', $orderCharge);

        $oldValues = $orderCharge->getOriginal();

        $orderCharge->update($data);

        ActivityLogService::logUpdate($orderCharge, $oldValues);

        return redirect()->route('admin.order_charges.index')->with('success', 'OrderCharge updated successfully.');
    }

    public function destroy(OrderCharge $orderCharge)
    {
        $this->deleteAssociatedFiles(OrderCharge::class, $orderCharge);

        ActivityLogService::logDelete($orderCharge);

        $orderCharge->delete();
        return redirect()->route('admin.order_charges.index')->with('success', 'OrderCharge deleted successfully.');
    }


}
