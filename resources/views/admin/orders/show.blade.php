@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
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

    <!-- Receipt Style Card -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 dark:border-gray-800 overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-start bg-gray-50 dark:bg-gray-800/30">
            <div class="flex-1">
                <div class="flex items-center gap-2.5">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</h2>
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                            'confirmed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                            'preparing' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-400',
                            'ready' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                            'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400',
                        ];
                        $sc = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md {{ $sc }}">{{ $order->status }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Customer Info</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $order->customer_name ?? 'Guest' }}</p>
                        @if($order->customer_email || $order->customer_phone)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $order->customer_phone }}
                                {{ $order->customer_phone && $order->customer_email ? ' • ' : '' }}
                                {{ $order->customer_email }}
                            </p>
                        @endif
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Order Info</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $order->order_type == 'dine_in' ? 'Dine In' : 'Take Away' }}
                            @if($order->order_type == 'dine_in' && $order->diningTable)
                                - Table {{ $order->diningTable->number }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">TOTAL</p>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-6">
            <!-- Items -->
            <div>
                <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Detail Item</h3>
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800 divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center text-xs font-bold text-gray-700 dark:text-gray-200">{{ $item->quantity }}x</span>
                                <div>
                                    <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $item->product->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    @if($item->notes)
                                    <p class="text-xs text-amber-600 dark:text-amber-500 mt-0.5">Notes: {{ $item->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="font-semibold text-sm text-gray-900 dark:text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
            <div class="flex items-start gap-2.5 p-4 bg-amber-50 dark:bg-amber-950/20 rounded-xl border border-amber-100 dark:border-amber-900/30">
                <span class="text-lg">Notes: </span>
                <div>
                    <p class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">Catatan Pesanan</p>
                    <p class="text-sm text-amber-800 dark:text-amber-300 leading-relaxed">{!! nl2br(e($order->notes)) !!}</p>
                </div>
            </div>
            @endif

            <!-- Summary -->
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800 p-5 space-y-2.5">
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-sm text-red-500 dark:text-red-400">
                    <span>Diskon {{ $order->promo ? '('.$order->promo->code.')' : '' }}</span>
                    <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                @foreach($order->charges as $charge)
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>{{ $charge->charge_name }}</span>
                        <span>Rp {{ number_format($charge->charge_amount, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between font-extrabold text-lg text-gray-900 dark:text-white pt-3 border-t border-gray-200 dark:border-gray-700 mt-2">
                    <span>Total Akhir</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Payment Status -->
        <div class="p-6 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/30">
            @if($order->payment_status == 'paid')
                <div class="flex items-center justify-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-bold text-sm text-emerald-700 dark:text-emerald-400">Paid in Full — {{ strtoupper($order->payment_method ?? 'CASH') }}</span>
                    @if($order->paid_at)
                        <span class="text-xs font-medium text-emerald-600 dark:text-emerald-500 ml-2">({{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y H:i') }})</span>
                    @endif
                </div>
            @else
                <div class="flex items-center justify-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <span class="font-bold text-sm text-red-700 dark:text-red-400">Belum Dibayar</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
