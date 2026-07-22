<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Simple Cafe Statistics
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $activeOrders = Order::whereNotIn('status', ['completed', 'cancelled'])->count();
        $totalProducts = Product::count();
        
        $recentOrders = Order::with('diningTable')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.pages.dashboard', compact(
            'totalOrders', 
            'totalRevenue', 
            'activeOrders', 
            'totalProducts',
            'recentOrders'
        ));
    }

    public function printPos($orderId)
    {
        $order = Order::with(['diningTable', 'items.product', 'charges'])->findOrFail($orderId);
        return view('admin.pos.print', compact('order'));
    }
}
