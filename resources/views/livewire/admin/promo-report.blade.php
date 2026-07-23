<div>
    <div class="mt-8 border-t border-gray-100 dark:border-gray-800 pt-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="lg:text-lg text-base font-semibold text-gray-900 dark:text-white">Usage Report</h3>

            <div class="w-64">
                <input type="text" x-data="{
                    init() {
                        flatpickr(this.$el, {
                            mode: 'range',
                            dateFormat: 'Y-m-d',
                            onChange: (selectedDates, dateStr, instance) => {
                                $wire.set('dateRange', dateStr);
                            }
                        });
                    }
                }"
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm py-2 px-3 text-gray-900 dark:text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Filter by Date Range (e.g. YYYY-MM-DD to YYYY-MM-DD)">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div
                class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/50">
                <div class="text-sm text-indigo-600 dark:text-indigo-400 font-medium mb-1">Total Usage</div>
                <div class="text-2xl font-bold text-indigo-900 dark:text-indigo-300">{{ $totalUsage }} times</div>
            </div>
            <div
                class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/50">
                <div class="text-sm text-emerald-600 dark:text-emerald-400 font-medium mb-1">Total Discount Given</div>
                <div class="text-2xl font-bold text-emerald-900 dark:text-emerald-300">Rp
                    {{ number_format($totalDiscount, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-100 dark:border-gray-800 rounded-xl">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Date</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Order No.</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Customer</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Discount</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->customer_name ?? 'Guest' }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">
                                Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <p>No transactions found for this promo.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
