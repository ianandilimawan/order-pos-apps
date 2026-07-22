<div wire:poll.10s="loadOrders" class="h-[calc(100vh-80px)] flex flex-col lg:flex-row gap-4">
    {{-- ============================== --}}
    {{-- LEFT: ORDER LIST --}}
    {{-- ============================== --}}
    <div class="w-full lg:w-[380px] flex-shrink-0 flex flex-col bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        {{-- Header --}}
        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <h2 class="font-bold text-gray-900 dark:text-white text-base">Pesanan Aktif</h2>
                <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ count($orders) }}</span>
            </div>
            <button wire:click="loadOrders" class="p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="Refresh">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>

        {{-- Order Cards --}}
        <div class="flex-1 overflow-y-auto p-3 space-y-2">
            @forelse($orders as $order)
                <div wire:click="selectOrder({{ $order->id }})"
                    class="p-3.5 rounded-xl border cursor-pointer transition-all duration-150
                    {{ $selectedOrder && $selectedOrder->id == $order->id
                        ? 'border-indigo-500 dark:border-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 ring-1 ring-indigo-500/20'
                        : 'border-gray-100 dark:border-gray-800 hover:border-gray-200 dark:hover:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">
                                {{ $order->created_at->format('H:i') }} •
                                {{ $order->order_type == 'dine_in' ? '🍽️ Dine In' : '🥡 Take Away' }}
                            </p>
                        </div>
                        @php
                            $statusColors = [
                                'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                                'confirmed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                                'preparing' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-400',
                                'ready' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                            ];
                        @endphp
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="flex justify-between items-end mt-3">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                            @if($order->order_type == 'dine_in' && $order->dining_table)
                                Meja {{ $order->dining_table->number }}
                            @else
                                {{ $order->notes ? Str::limit($order->notes, 20) : '-' }}
                            @endif
                        </span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-400 dark:text-gray-500">Belum ada pesanan aktif</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ============================== --}}
    {{-- RIGHT: ORDER DETAIL --}}
    {{-- ============================== --}}
    <div class="flex-1 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col overflow-hidden">
        @if($selectedOrder)
            {{-- Detail Header --}}
            <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedOrder->order_number }}</h2>
                        @php
                            $sc = $statusColors[$selectedOrder->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md {{ $sc }}">{{ $selectedOrder->status }}</span>
                    </div>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                        {{ $selectedOrder->order_type == 'dine_in' ? '🍽️ Dine In' : '🥡 Take Away' }}
                        @if($selectedOrder->order_type == 'dine_in' && $selectedOrder->diningTable)
                            • Meja {{ $selectedOrder->diningTable->number }}
                        @endif
                        • {{ $selectedOrder->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">TOTAL</p>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">Rp {{ number_format($selectedOrder->total, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Detail Content --}}
            <div class="flex-1 overflow-y-auto p-5 space-y-6">

                {{-- Status Actions --}}
                @if($selectedOrder->payment_status !== 'paid')
                <div>
                    <h3 class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Status Pesanan</h3>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $steps = [
                                'confirmed' => ['label' => 'Konfirmasi', 'icon' => '✓', 'color' => 'blue'],
                                'preparing' => ['label' => 'Diproses', 'icon' => '🍳', 'color' => 'purple'],
                                'ready' => ['label' => 'Siap', 'icon' => '✅', 'color' => 'emerald'],
                            ];
                        @endphp
                        @foreach($steps as $key => $step)
                            <button wire:click="updateOrderStatus('{{ $key }}')"
                                class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all duration-150
                                {{ $selectedOrder->status == $key
                                    ? 'bg-' . $step['color'] . '-600 text-white border-' . $step['color'] . '-600 shadow-sm'
                                    : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                {{ $step['icon'] }} {{ $step['label'] }}
                            </button>
                        @endforeach
                        <button wire:click="updateOrderStatus('cancelled')"
                            class="px-4 py-2 rounded-xl text-sm font-semibold border border-red-200 dark:border-red-900/50 text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors ml-auto">
                            ✕ Batal
                        </button>
                    </div>
                </div>
                @endif

                {{-- Items --}}
                <div>
                    <h3 class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Detail Item</h3>
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800 divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($selectedOrder->items as $item)
                            <div class="flex justify-between items-center px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-7 h-7 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center text-xs font-bold text-gray-700 dark:text-gray-200">{{ $item->quantity }}x</span>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $item->product->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <span class="font-semibold text-sm text-gray-900 dark:text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Notes --}}
                @if($selectedOrder->notes)
                <div class="flex items-start gap-2.5 p-3.5 bg-amber-50 dark:bg-amber-950/20 rounded-xl border border-amber-100 dark:border-amber-900/30">
                    <span class="text-lg">📝</span>
                    <div>
                        <p class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">Catatan</p>
                        <p class="text-sm text-amber-800 dark:text-amber-300 leading-relaxed">{!! nl2br(e($selectedOrder->notes)) !!}</p>
                    </div>
                </div>
                @endif

                {{-- Summary --}}
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800 p-4 space-y-2">
                    <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($selectedOrder->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @foreach($selectedOrder->charges as $charge)
                        <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                            <span>{{ $charge->charge_name }}</span>
                            <span>Rp {{ number_format($charge->charge_amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="flex justify-between font-extrabold text-lg text-gray-900 dark:text-white pt-2.5 border-t border-gray-200 dark:border-gray-700 mt-1">
                        <span>Total</span>
                        <span>Rp {{ number_format($selectedOrder->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- ============================== --}}
            {{-- FOOTER: PAYMENT --}}
            {{-- ============================== --}}
            <div class="p-5 border-t border-gray-100 dark:border-gray-800">
                @if($selectedOrder->payment_status == 'paid' || $selectedOrder->status == 'completed')
                    {{-- Paid State --}}
                    <div class="bg-emerald-50 dark:bg-emerald-950/20 rounded-xl border border-emerald-100 dark:border-emerald-900/30 p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-bold text-sm text-emerald-700 dark:text-emerald-400">Lunas — {{ ucfirst($selectedOrder->payment_method ?? 'Cash') }}</span>
                            @if($selectedOrder->paid_at)
                                <span class="text-xs text-emerald-500 dark:text-emerald-500 ml-auto">{{ $selectedOrder->paid_at->format('H:i') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('admin.pos.print', $selectedOrder->id) }}" target="_blank"
                            class="w-full py-3 bg-gray-900 hover:bg-black text-white rounded-xl font-bold text-sm shadow-sm transition-colors text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Struk
                        </a>
                    </div>
                @else
                    {{-- Payment Buttons --}}
                    <h3 class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Proses Pembayaran</h3>
                    <div class="grid grid-cols-3 gap-2.5">
                        <button wire:click="markAsPaid('cash')"
                            class="py-3.5 rounded-xl border-2 border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-emerald-400 dark:hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 font-semibold text-sm text-gray-700 dark:text-gray-200 transition-all flex flex-col items-center gap-1.5 group">
                            <span class="text-2xl group-hover:scale-110 transition-transform">💵</span>
                            <span>Cash</span>
                        </button>
                        <button wire:click="markAsPaid('qris')"
                            class="py-3.5 rounded-xl border-2 border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/20 font-semibold text-sm text-gray-700 dark:text-gray-200 transition-all flex flex-col items-center gap-1.5 group">
                            <span class="text-2xl group-hover:scale-110 transition-transform">📱</span>
                            <span>QRIS</span>
                        </button>
                        <button wire:click="markAsPaid('transfer')"
                            class="py-3.5 rounded-xl border-2 border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-violet-400 dark:hover:border-violet-500 hover:bg-violet-50 dark:hover:bg-violet-950/20 font-semibold text-sm text-gray-700 dark:text-gray-200 transition-all flex flex-col items-center gap-1.5 group">
                            <span class="text-2xl group-hover:scale-110 transition-transform">🏦</span>
                            <span>Transfer</span>
                        </button>
                    </div>
                @endif
            </div>

        @else
            {{-- Empty State --}}
            <div class="flex-1 flex flex-col items-center justify-center p-10">
                <div class="w-16 h-16 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-200 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Pilih Pesanan</h3>
                <p class="text-sm text-gray-400 dark:text-gray-500 text-center">Klik salah satu pesanan di sebelah kiri untuk melihat detail dan memproses pembayaran.</p>
            </div>
        @endif
    </div>
</div>
