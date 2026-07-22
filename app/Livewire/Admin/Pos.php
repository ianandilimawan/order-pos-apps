<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;

class Pos extends Component
{
    public $orders = [];
    public $selectedOrder = null;

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        // Get active orders (not completed/cancelled)
        $this->orders = Order::with(['diningTable', 'items.product', 'charges'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = Order::with(['diningTable', 'items.product', 'charges'])->find($orderId);
    }

    public function updateOrderStatus($status)
    {
        if (!$this->selectedOrder) return;
        
        $oldValues = $this->selectedOrder->getAttributes();
        $this->selectedOrder->update([
            'status' => $status,
            'user_id' => auth()->id() // Link to the kasir handling this
        ]);
        
        ActivityLogService::logUpdate($this->selectedOrder, $oldValues, "Updated order status to {$status}");
        
        $this->dispatch('notify', type: 'success', message: 'Status updated to ' . ucfirst($status));
        $this->loadOrders();
        $this->selectOrder($this->selectedOrder->id);
    }

    public function markAsPaid($paymentMethod)
    {
        if (!$this->selectedOrder) return;
        
        $oldValues = $this->selectedOrder->getAttributes();
        
        $this->selectedOrder->update([
            'payment_status' => 'paid',
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
            'status' => 'completed', // usually completes when paid
            'user_id' => auth()->id() // Link to the kasir handling the payment
        ]);
        
        ActivityLogService::logUpdate($this->selectedOrder, $oldValues, "Processed {$paymentMethod} payment for order");
        
        $this->dispatch('notify', type: 'success', message: 'Payment successful!');
        
        // Keep the order selected so the user can print the receipt
        $this->selectOrder($this->selectedOrder->id);
        $this->loadOrders();
    }

    public function render()
    {
        // Polling every 10s to get new orders
        return view('livewire.admin.pos')
            ->extends('admin.layouts.app')
            ->section('content');
    }
}
