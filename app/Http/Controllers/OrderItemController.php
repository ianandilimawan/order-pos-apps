<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\OrderItem;
use App\Http\Requests\CreateOrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;


class OrderItemController extends Controller
{
        use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-order_items')->only(['index', 'show']);
        $this->middleware('permission:create-order_items')->only(['create', 'store']);
        $this->middleware('permission:edit-order_items')->only(['edit', 'update']);
        $this->middleware('permission:delete-order_items')->only('destroy');
    }

    public function index()
    {
        return view('admin.order_items.index');
    }

    public function create()
    {
        $orderItem = new OrderItem();
        $fileUrls = $this->getFileUrls(OrderItem::class);
        $orders = \App\Models\Order::pluck('order_number', 'id');
        $products = \App\Models\Product::pluck('name', 'id');

        return view('admin.order_items.create', compact('orderItem', 'fileUrls', 'orders', 'products'));
    }

    public function store(CreateOrderItemRequest $request)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, OrderItem::class, 'order_item');

        $orderItem = OrderItem::create($data);

        ActivityLogService::logCreate($orderItem);

        return redirect()->route('admin.order_items.index')->with('success', 'OrderItem created successfully.');
    }

    public function show(OrderItem $orderItem)
    {
        return view('admin.order_items.show', compact('orderItem'));
    }

    public function edit(OrderItem $orderItem)
    {
        $fileUrls = $this->getFileUrls(OrderItem::class, $orderItem);
        $orders = \App\Models\Order::pluck('order_number', 'id');
        $products = \App\Models\Product::pluck('name', 'id');

        return view('admin.order_items.edit', compact('orderItem', 'fileUrls', 'orders', 'products'));
    }

    public function update(UpdateOrderItemRequest $request, OrderItem $orderItem)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, OrderItem::class, 'order_item', $orderItem);

        $oldValues = $orderItem->getOriginal();

        $orderItem->update($data);

        ActivityLogService::logUpdate($orderItem, $oldValues);

        return redirect()->route('admin.order_items.index')->with('success', 'OrderItem updated successfully.');
    }

    public function destroy(OrderItem $orderItem)
    {
        $this->deleteAssociatedFiles(OrderItem::class, $orderItem);

        ActivityLogService::logDelete($orderItem);

        $orderItem->delete();
        return redirect()->route('admin.order_items.index')->with('success', 'OrderItem deleted successfully.');
    }


}
