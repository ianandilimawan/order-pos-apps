<div class="mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center {{ $hideTitle ? 'justify-end' : 'justify-between' }} gap-4">
        @if(!$hideTitle)
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sales Report</h1>
            <p class="text-sm text-gray-500 mt-1">Sales and product performance summary</p>
        </div>
        @endif
        <div
            class="flex items-center gap-2 bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <input type="date" wire:model.live="startDate"
                class="border-none bg-transparent text-sm font-medium focus:ring-0 cursor-pointer text-gray-700 dark:text-gray-200">
            <span class="text-gray-400">-</span>
            <input type="date" wire:model.live="endDate"
                class="border-none bg-transparent text-sm font-medium focus:ring-0 cursor-pointer text-gray-700 dark:text-gray-200">
            <button wire:click="exportCsv"
                class="ml-2 flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white dark:bg-indigo-500 dark:hover:bg-indigo-600 text-xs font-bold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                    </path>
                </svg>
                Export CSV
            </button>
        </div>
    </div>

    @php
        $metrics = $this->metrics;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Main Stats -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-center relative overflow-hidden">
            <div
                class="absolute -right-6 -top-6 w-32 h-32 bg-indigo-50 dark:bg-indigo-900/20 rounded-full blur-3xl opacity-60">
            </div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Total Revenue</p>
            <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white">Rp
                {{ number_format($metrics['totalSales'], 0, ',', '.') }}</h2>
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span
                    class="inline-flex items-center gap-1 bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded-md text-xs font-bold">
                    {{ $metrics['orderCount'] }} Completed Orders
                </span>
                @if($metrics['promoUsageCount'] > 0)
                <span
                    class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400 px-2 py-1 rounded-md text-xs font-bold"
                    title="Promo usage count">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    {{ $metrics['promoUsageCount'] }} Promo Used
                </span>
                <span
                    class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400 px-2 py-1 rounded-md text-xs font-bold"
                    title="Total discounts given">
                    - Rp {{ number_format($metrics['totalDiscounts'], 0, ',', '.') }} Disc
                </span>
                @endif
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-4 uppercase tracking-wider">Payment
                Methods</h3>
            <div class="space-y-4">
                @forelse($metrics['paymentMethods'] as $method => $data)
                    <div>
                        <div class="flex justify-between text-sm font-bold text-gray-900 dark:text-white mb-1.5">
                            <span class="uppercase">{{ $method ?: 'Unknown' }} ({{ $data['count'] }})</span>
                            <span>Rp {{ number_format($data['total'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full"
                                style="width: {{ $metrics['totalSales'] > 0 ? ($data['total'] / $metrics['totalSales']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No payment data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Top Products</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead
                    class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4 text-center">Sold</th>
                        <th class="px-6 py-4 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($metrics['topProducts'] as $index => $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <span class="w-6 text-gray-400 font-bold">#{{ $index + 1 }}</span>
                                <span
                                    class="font-bold text-gray-900 dark:text-white">{{ $item->product->name ?? 'Deleted Product' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center font-semibold">
                                {{ $item->total_qty }} items
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400">
                                Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-400">No sales data in this date
                                range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Promo Usage -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Promo Usage</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Promo Code</th>
                        <th class="px-6 py-4 text-center">Used</th>
                        <th class="px-6 py-4 text-right">Total Discount Given</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($metrics['promoDetails'] ?? collect() as $index => $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <span class="w-6 text-gray-400 font-bold">#{{ $index + 1 }}</span>
                                <div>
                                    <span class="font-bold text-gray-900 dark:text-white block">{{ $item['promo']->code ?? 'Deleted Promo' }}</span>
                                    @if(isset($item['promo']->name))
                                        <span class="text-xs text-gray-500">{{ $item['promo']->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-semibold">
                                {{ $item['usage_count'] }} times
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-rose-600 dark:text-rose-400">
                                - Rp {{ number_format($item['total_discount'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-400">No promos used in this date range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
