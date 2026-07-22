<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            // Advanced Analytics for Admin
            $totalOrders = Order::count();
            $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
            $activeOrders = Order::whereNotIn('status', ['completed', 'cancelled'])->count();
            $totalProducts = Product::count();
            
            $recentOrders = Order::with('diningTable')->orderBy('created_at', 'desc')->take(5)->get();
            $dailyRevenue = Order::where('payment_status', 'paid')
                                ->whereDate('created_at', \Carbon\Carbon::today())
                                ->sum('total');
            $cashOpnames = \App\Models\CashOpname::with('user')->orderBy('created_at', 'desc')->take(5)->get();

            return view('admin.pages.dashboard', compact(
                'totalOrders', 
                'totalRevenue', 
                'activeOrders', 
                'totalProducts',
                'recentOrders',
                'dailyRevenue',
                'cashOpnames'
            ));
        } else {
            // Simple Task-Oriented Dashboard for Kasir
            $userId = $user->id;
            $today = \Carbon\Carbon::today();
            
            $myOrdersToday = Order::where('user_id', $userId)
                                ->whereDate('created_at', $today)
                                ->count();
            $myRevenueToday = Order::where('user_id', $userId)
                                ->whereDate('created_at', $today)
                                ->where('payment_status', 'paid')
                                ->sum('total');
                                
            return view('admin.pages.dashboard_kasir', compact(
                'myOrdersToday',
                'myRevenueToday'
            ));
        }
    }

    public function printPos($orderId)
    {
        $order = Order::with(['diningTable', 'items.product', 'charges'])->findOrFail($orderId);
        return view('admin.pos.print', compact('order'));
    }
}
