@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header & Breadcrumbs -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.orders.index') }}" class="p-2 bg-white dark:bg-gray-800 rounded-full shadow-sm border border-gray-100 dark:border-gray-700 text-gray-500 hover:text-indigo-600 hover:bg-gray-50 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <nav class="flex text-sm text-gray-500 font-medium mb-1" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.orders.index') }}" class="hover:text-indigo-600 transition-colors">Orders</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <span class="text-gray-400">Details</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="lg:text-2xl text-xl font-semibold text-gray-900 dark:text-white">Order Details</h1>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pos.print', $order->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-900 transition-colors text-sm font-semibold shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Struk
            </a>
            <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors text-sm font-semibold shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Order
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Side: Basic Info & Payment -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Order Info Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 dark:border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Info</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($order->status == 'completed') bg-green-100 text-green-800
                        @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif
                    ">
                        {{ ucfirst($order->status ?? 'N/A') }}
                    </span>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Order Number</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $order->order_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Order Type</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($order->order_type ?? 'N/A') }}</p>
                    </div>
                    @if($order->dining_table_id)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Table</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $order->diningTable->number ?? $order->dining_table_id }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Date</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($order->notes)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Notes</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 dark:border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($order->payment_status == 'paid') bg-green-100 text-green-800
                        @elseif($order->payment_status == 'unpaid') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif
                    ">
                        {{ ucfirst($order->payment_status ?? 'N/A') }}
                    </span>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Method</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ strtoupper($order->payment_method ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Paid At</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $order->paid_at ? \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Order Items -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 dark:border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Details</h3>
                </div>
                
                <div class="p-6">
                    <!-- Items List -->
                    <div class="space-y-4">
                        @foreach($order->items ?? [] as $item)
                        <div class="flex justify-between items-start pb-4 border-b border-gray-100 dark:border-gray-800 last:border-0 last:pb-0">
                            <div class="flex gap-4">
                                @if($item->product && $item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" class="w-12 h-12 rounded-lg object-cover bg-gray-100" alt="{{ $item->product->name }}">
                                @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                @endif
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $item->product->name ?? 'Product Not Found' }}</h4>
                                    <p class="text-sm text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                                    @if($item->notes)
                                    <p class="text-xs text-gray-500 mt-1">Note: {{ $item->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Summary -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            
                            @foreach($order->charges ?? [] as $charge)
                            <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                <span>{{ $charge->charge_name }} ({{ $charge->charge_type == 'percentage' ? $charge->charge_rate.'%' : 'Fixed' }})</span>
                                <span>Rp {{ number_format($charge->charge_amount, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            
                            <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                                <span class="font-semibold text-lg text-gray-900 dark:text-white">Total</span>
                                <span class="font-bold text-xl text-indigo-600 dark:text-indigo-400">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
