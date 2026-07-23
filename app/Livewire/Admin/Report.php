<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class Report extends Component
{
    public $startDate;
    public $endDate;
    public $hideTitle = false;

    public function mount($hideTitle = false)
    {
        $this->hideTitle = $hideTitle;
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
    }

    public function getMetricsProperty()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $orders = Order::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->get();

        $totalSales = $orders->sum('total');
        $orderCount = $orders->count();
        
        // Group by payment method
        $paymentMethods = $orders->groupBy('payment_method')->map(function ($row) {
            return [
                'count' => $row->count(),
                'total' => $row->sum('total')
            ];
        });

        // Best selling products
        $topProducts = OrderItem::whereHas('order', function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->where('payment_status', 'paid');
            })
            ->with('product')
            ->select('product_id', \DB::raw('SUM(quantity) as total_qty'), \DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Promo metrics
        $totalDiscounts = $orders->sum('discount_amount');
        $promoUsageCount = $orders->whereNotNull('promo_id')->count();

        // Promo details
        $promoDetails = $orders->whereNotNull('promo_id')
            ->groupBy('promo_id')
            ->map(function ($row) {
                return [
                    'promo' => $row->first()->promo,
                    'usage_count' => $row->count(),
                    'total_discount' => $row->sum('discount_amount')
                ];
            })->sortByDesc('usage_count')->values();

        return [
            'totalSales' => $totalSales,
            'orderCount' => $orderCount,
            'paymentMethods' => $paymentMethods,
            'topProducts' => $topProducts,
            'totalDiscounts' => $totalDiscounts,
            'promoUsageCount' => $promoUsageCount,
            'promoDetails' => $promoDetails,
        ];
    }

    public function exportCsv()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $orders = Order::with(['items.product', 'user'])
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=Laporan_Penjualan_{$this->startDate}_to_{$this->endDate}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Order Number', 'Date', 'Type', 'Status', 'Payment Method', 'Items', 'Total'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $items = $order->items->map(function($item) {
                    return $item->quantity . 'x ' . ($item->product->name ?? 'Unknown');
                })->implode(', ');

                $row['Order Number']  = $order->order_number;
                $row['Date']    = $order->created_at->format('Y-m-d H:i');
                $row['Type']  = $order->order_type;
                $row['Status']  = $order->status;
                $row['Payment Method']  = $order->payment_method;
                $row['Items']  = $items;
                $row['Total']  = $order->total;

                fputcsv($file, array($row['Order Number'], $row['Date'], $row['Type'], $row['Status'], $row['Payment Method'], $row['Items'], $row['Total']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.admin.report')
            ->extends('admin.layouts.app')
            ->section('content');
    }
}
