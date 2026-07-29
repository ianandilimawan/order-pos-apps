@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex justify-between items-end animate-fade-in-up">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white tracking-tight">Cafe Dashboard
                </h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Overview of your cafe's daily performance.</p>
            </div>
            <div class="hidden sm:flex space-x-2">
                <a href="{{ route('admin.pos') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm shadow-blue-500/30 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    Open POS
                </a>
            </div>
        </div>

        <!-- High-level Summary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in-up delay-100">
            
            <!-- Revenue Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-6 flex items-center justify-between transition-transform hover:-translate-y-1">
                <div>
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Today's Revenue</p>
                    <h3 class="text-3xl font-bold text-zinc-900 dark:text-white">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="p-4 bg-green-50 dark:bg-green-500/10 rounded-full">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Orders Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-6 flex items-center justify-between transition-transform hover:-translate-y-1">
                <div>
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Today's Orders</p>
                    <h3 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($todayOrders) }}</h3>
                </div>
                <div class="p-4 bg-blue-50 dark:bg-blue-500/10 rounded-full">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 animate-fade-in-up delay-200">
            <!-- Recent Orders -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-700 flex justify-between items-center">
                    <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-white">Recent Orders</h2>
                    <a href="{{ route('admin.orders.index') }}"
                        class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Order No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-white">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ in_array($order->status, ['confirmed', 'preparing', 'ready']) ? 'bg-blue-100 text-blue-800' : '' }}
                                        ">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-zinc-900 dark:text-white">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400 text-sm">
                                        No orders yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cash Opnames -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-700 flex justify-between items-center">
                    <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-white">Recent Cash Opnames
                    </h2>
                    <a href="{{ route('admin.cash_opnames.index') }}"
                        class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    User</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Diff</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($cashOpnames ?? [] as $opname)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                        {{ $opname->opname_date->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                        {{ $opname->user->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $opname->status->value == 'matched' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $opname->status->value == 'shortage' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $opname->status->value == 'overage' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        ">
                                            {{ ucfirst($opname->status->value) }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $opname->difference < 0 ? 'text-red-500' : ($opname->difference > 0 ? 'text-yellow-500' : 'text-zinc-900 dark:text-white') }}">
                                        Rp {{ number_format($opname->difference, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400 text-sm">
                                        No cash opnames yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
