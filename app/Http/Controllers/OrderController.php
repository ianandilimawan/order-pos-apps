<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\Order;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;


class OrderController extends Controller
{
    use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-orders')->only(['index', 'show']);
        $this->middleware('permission:create-order')->only(['create', 'store']);
        $this->middleware('permission:edit-order')->only(['edit', 'update']);
        $this->middleware('permission:delete-order')->only('destroy');
    }

    public function index()
    {
        return view('admin.orders.index');
    }

    public function create()
    {
        $order = new Order();
        $fileUrls = $this->getFileUrls(Order::class);
        $diningTables = \App\Models\DiningTable::pluck('number', 'id');

        return view('admin.orders.create', compact('order', 'fileUrls', 'diningTables'));
    }

    public function store(CreateOrderRequest $request)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, Order::class, 'order');

        $order = Order::create($data);

        ActivityLogService::logCreate($order);

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $fileUrls = $this->getFileUrls(Order::class, $order);
        $diningTables = \App\Models\DiningTable::pluck('number', 'id');

        return view('admin.orders.edit', compact('order', 'fileUrls', 'diningTables'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, Order::class, 'order', $order);

        $oldValues = $order->getOriginal();

        $order->update($data);

        ActivityLogService::logUpdate($order, $oldValues);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $this->deleteAssociatedFiles(Order::class, $order);

        ActivityLogService::logDelete($order);

        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
