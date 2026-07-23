<div wire:poll.3s="loadOrders" class="h-[calc(100vh-80px)] flex flex-col gap-4">
    {{-- Header Toggle --}}
    <div
        class="flex bg-white dark:bg-gray-900 rounded-xl p-1 shadow-sm border border-gray-100 dark:border-gray-800 self-start">
        <button wire:click="switchMode('list')"
            class="px-5 py-2 rounded-lg text-sm font-bold transition-all {{ $mode === 'list' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">
            Order List
        </button>
        <button wire:click="switchMode('create')"
            class="px-5 py-2 rounded-lg text-sm font-bold transition-all {{ $mode === 'create' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">
            New Order
        </button>
    </div>

    @if ($mode === 'list')
        {{-- LIST MODE --}}
        <div class="flex-1 flex flex-col lg:flex-row gap-4 min-h-0">
            {{-- ============================== --}}
            {{-- LEFT: ORDER LIST --}}
            {{-- ============================== --}}
            <div
                class="w-full lg:w-[380px] flex-shrink-0 flex flex-col bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                {{-- Header --}}
                <div
                    class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-gray-800/30">
                    <div class="flex items-center gap-3">
                        <h2 class="font-bold text-gray-900 dark:text-white text-base">Active Orders</h2>
                        <span
                            class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ count($orders) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.cash_opnames.create') }}"
                            onclick="event.preventDefault(); Swal.fire({ title: 'Close Shift?', text: 'Are you sure you want to close the current cashier shift?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#4f46e5', cancelButtonColor: '#d33', confirmButtonText: 'Yes, Close Shift!', cancelButtonText: 'Cancel' }).then((result) => { if (result.isConfirmed) { window.location.href = this.href; } });"
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm"
                            title="Close Shift (Cash Opname)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Close Shift
                        </a>
                        <button wire:click="loadOrders"
                            class="p-1.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-white dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition-colors bg-white dark:bg-gray-900 shadow-sm"
                            title="Refresh">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                        </button>
                    </div>
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
                                    <p class="font-bold text-sm text-gray-900 dark:text-white">
                                        {{ $order->order_number }}
                                        @if ($order->customer_name)
                                            <span
                                                class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold ml-1">({{ $order->customer_name }})</span>
                                        @endif
                                    </p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">
                                        {{ $order->created_at->format('H:i') }} •
                                        {{ $order->order_type == 'dine_in' ? 'Dine In' : 'Take Away' }}
                                    </p>
                                </div>
                                @php
                                    $statusColors = [
                                        'pending' =>
                                            'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                                        'confirmed' =>
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                                        'preparing' =>
                                            'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-400',
                                        'ready' =>
                                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $order->status }}
                                </span>
                            </div>

                            <div class="flex justify-between items-end mt-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                    @if ($order->customer_name)
                                        <span
                                            class="font-semibold text-gray-700 dark:text-gray-300">{{ $order->customer_name }}</span>
                                        •
                                    @endif
                                    @if ($order->order_type == 'dine_in' && $order->diningTable)
                                        Table {{ $order->diningTable->number }}
                                    @else
                                        {{ $order->order_type == 'dine_in' ? 'Dine In' : 'Take Away' }}
                                    @endif
                                </span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">Rp
                                    {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div
                                class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-gray-300 dark:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-400 dark:text-gray-500">No active orders</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ============================== --}}
            {{-- RIGHT: ORDER DETAIL --}}
            {{-- ============================== --}}
            <div
                class="flex-1 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col overflow-hidden">
                @if ($selectedOrder)
                    {{-- Detail Header --}}
                    <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $selectedOrder->order_number }}</h2>
                                @php
                                    $sc = $statusColors[$selectedOrder->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span
                                    class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md {{ $sc }}">{{ $selectedOrder->status }}</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">
                                        Customer Info</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $selectedOrder->customer_name ?? 'Guest' }}</p>
                                    @if ($selectedOrder->customer_email || $selectedOrder->customer_phone)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $selectedOrder->customer_phone }}
                                            {{ $selectedOrder->customer_phone && $selectedOrder->customer_email ? ' • ' : '' }}
                                            {{ $selectedOrder->customer_email }}
                                        </p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Order
                                        Info</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $selectedOrder->order_type == 'dine_in' ? 'Dine In' : 'Take Away' }}
                                        @if ($selectedOrder->order_type == 'dine_in' && $selectedOrder->diningTable)
                                            - Table {{ $selectedOrder->diningTable->number }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $selectedOrder->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">TOTAL</p>
                            <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">Rp
                                {{ number_format($selectedOrder->total, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Detail Content --}}
                    <div class="flex-1 overflow-y-auto p-5 space-y-6">

                        {{-- Status Actions Removed --}}

                        {{-- Items --}}
                        <div>
                            <h3
                                class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                                Detail Item</h3>
                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800 divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach ($selectedOrder->items as $item)
                                    <div class="flex justify-between items-center px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="w-7 h-7 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center text-xs font-bold text-gray-700 dark:text-gray-200">{{ $item->quantity }}x</span>
                                            <div>
                                                <p class="font-semibold text-sm text-gray-900 dark:text-white">
                                                    {{ $item->product->name ?? 'Unknown' }}</p>
                                                @if ($item->notes)
                                                    <p class="text-xs text-amber-600 dark:text-amber-500 mt-0.5">
                                                        Notes: {{ $item->notes }}</p>
                                                @endif
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">@ Rp
                                                    {{ number_format($item->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <span class="font-semibold text-sm text-gray-900 dark:text-white">Rp
                                            {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Notes --}}
                        @if ($selectedOrder->notes)
                            <div
                                class="flex items-start gap-2.5 p-3.5 bg-amber-50 dark:bg-amber-950/20 rounded-xl border border-amber-100 dark:border-amber-900/30">
                                <span class="text-lg">Notes: </span>
                                <div>
                                    <p
                                        class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">
                                        Catatan</p>
                                    <p class="text-sm text-amber-800 dark:text-amber-300 leading-relaxed">
                                        {!! nl2br(e($selectedOrder->notes)) !!}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Summary --}}
                        <div
                            class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800 p-4 space-y-2">
                            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($selectedOrder->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if ($selectedOrder->discount_amount > 0)
                                <div class="flex justify-between text-sm text-red-500 dark:text-red-400">
                                    <span>Discount</span>
                                    <span>-Rp {{ number_format($selectedOrder->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @foreach ($selectedOrder->charges as $charge)
                                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <span>{{ $charge->charge_name }}</span>
                                    <span>Rp {{ number_format($charge->charge_amount, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            <div
                                class="flex justify-between font-extrabold text-lg text-gray-900 dark:text-white pt-2.5 border-t border-gray-200 dark:border-gray-700 mt-1">
                                <span>Total</span>
                                <span>Rp {{ number_format($selectedOrder->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER: PAYMENT --}}
                    <div class="p-5 border-t border-gray-100 dark:border-gray-800">
                        @if ($selectedOrder->payment_status == 'paid' || $selectedOrder->status == 'completed')
                            {{-- Paid State --}}
                            <div
                                class="bg-emerald-50 dark:bg-emerald-950/20 rounded-xl border border-emerald-100 dark:border-emerald-900/30 p-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="font-bold text-sm text-emerald-700 dark:text-emerald-400">Lunas —
                                        {{ ucfirst($selectedOrder->payment_method ?? 'Cash') }}</span>
                                    @if ($selectedOrder->paid_at)
                                        <span
                                            class="text-xs text-emerald-500 dark:text-emerald-500 ml-auto">{{ \Carbon\Carbon::parse($selectedOrder->paid_at)->format('H:i') }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('admin.pos.print', $selectedOrder->id) }}" target="_blank"
                                    class="w-full py-3 bg-gray-900 hover:bg-black text-white rounded-xl font-bold text-sm shadow-sm transition-colors text-center flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    Cetak Struk
                                </a>
                            </div>
                        @else
                            {{-- Payment Buttons --}}
                            {{-- Payment Section --}}
                            <div x-data="{ 
                                method: '',
                                cashAmount: '',
                                get change() {
                                    const val = String(this.cashAmount || '0').replace(/\D/g, '');
                                    const amount = parseInt(val) || 0;
                                    return Math.max(0, amount - {{ $selectedOrder->total }});
                                },
                                formatRupiah(number) {
                                    return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(number);
                                }
                            }">
                                <h3 class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                                    Payment Method
                                </h3>
                                
                                <div class="grid grid-cols-3 gap-2.5 mb-4">
                                    <button @click="method = 'cash'"
                                        :class="method === 'cash' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400' : 'border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200'"
                                        class="py-3.5 rounded-xl border-2 hover:border-emerald-400 dark:hover:border-emerald-500 font-semibold text-sm transition-all flex flex-col items-center gap-1.5 group">
                                        <span class="text-2xl group-hover:scale-110 transition-transform">💵</span>
                                        <span>Cash</span>
                                    </button>
                                    <button @click="method = 'qris'"
                                        :class="method === 'qris' ? 'border-blue-500 bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400' : 'border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200'"
                                        class="py-3.5 rounded-xl border-2 hover:border-blue-400 dark:hover:border-blue-500 font-semibold text-sm transition-all flex flex-col items-center gap-1.5 group">
                                        <span class="text-2xl group-hover:scale-110 transition-transform">📱</span>
                                        <span>QRIS</span>
                                    </button>
                                    <button @click="method = 'transfer'"
                                        :class="method === 'transfer' ? 'border-violet-500 bg-violet-50 dark:bg-violet-950/20 text-violet-700 dark:text-violet-400' : 'border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200'"
                                        class="py-3.5 rounded-xl border-2 hover:border-violet-400 dark:hover:border-violet-500 font-semibold text-sm transition-all flex flex-col items-center gap-1.5 group">
                                        <span class="text-2xl group-hover:scale-110 transition-transform">🏦</span>
                                        <span>Transfer</span>
                                    </button>
                                </div>

                                {{-- Cash Calculator --}}
                                <div x-show="method === 'cash'" style="display: none;" class="mb-4">
                                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700 space-y-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Uang Diterima (Rp)</label>
                                            <input type="text" x-model="cashAmount" 
                                                x-on:input="cashAmount = cashAmount.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                                class="w-full text-lg font-bold rounded-lg border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-2.5 px-3"
                                                placeholder="Contoh: 100.000">
                                        </div>
                                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Kembalian:</span>
                                            <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400">
                                                Rp <span x-text="formatRupiah(change)"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Button --}}
                                <button x-show="method !== ''" style="display: none;" @click="$wire.markAsPaid(method)"
                                    class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm shadow-sm transition-colors text-center flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Proses Pembayaran
                                </button>
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="flex-1 flex flex-col items-center justify-center p-10">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-200 dark:text-gray-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Select Order</h3>
                        <p class="text-sm text-gray-400 dark:text-gray-500 text-center">Select an order to view details
                            and process payment.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($mode === 'create')
        {{-- CREATE MODE --}}
        <div class="flex-1 flex flex-col lg:flex-row gap-4 min-h-0">
            {{-- ============================== --}}
            {{-- LEFT: PRODUCT GRID --}}
            {{-- ============================== --}}
            <div
                class="flex-1 flex flex-col min-h-0 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                <!-- Search & Categories -->
                <div class="p-4 border-b border-gray-100 dark:border-gray-800 space-y-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search menu..."
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl leading-5 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors">
                    </div>

                    <div class="flex overflow-x-auto hide-scrollbar gap-2 pb-1">
                        <button wire:click="selectCategory(null)"
                            class="whitespace-nowrap px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ is_null($selectedCategoryId) ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">All</button>
                        @foreach ($this->categories as $cat)
                            <button wire:click="selectCategory({{ $cat->id }})"
                                class="whitespace-nowrap px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $selectedCategoryId == $cat->id ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">{{ $cat->name }}</button>
                        @endforeach
                    </div>
                </div>

                <!-- Products -->
                <div class="flex-1 overflow-y-auto p-4">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($this->products as $prod)
                            <div wire:click="addToCart({{ $prod->id }})"
                                class="group cursor-pointer bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:border-indigo-300 dark:hover:border-indigo-700 transition-all">
                                <div class="aspect-w-1 aspect-h-1 bg-gray-100 dark:bg-gray-900 relative">
                                    @if ($prod->image)
                                        <img src="{{ Storage::url($prod->image) }}" class="w-full h-32 object-cover"
                                            alt="{{ $prod->name }}">
                                    @else
                                        <div class="w-full h-32 flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                        <div
                                            class="opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all bg-indigo-600 text-white rounded-full p-2 shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <h3
                                        class="font-bold text-gray-900 dark:text-white text-sm line-clamp-2 leading-tight">
                                        {{ $prod->name }}</h3>
                                    <p class="text-indigo-600 dark:text-indigo-400 font-extrabold text-sm mt-1">Rp
                                        {{ number_format($prod->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-10 text-center text-gray-500">
                                No products found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ============================== --}}
            {{-- RIGHT: CART --}}
            {{-- ============================== --}}
            <div
                class="w-full lg:w-[550px] flex-shrink-0 flex flex-col bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div
                    class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-gray-800/30">
                    <h2 class="font-bold text-gray-900 dark:text-white text-base">New Cart</h2>
                    @if (count($cart) > 0)
                        <button wire:click="clearCart"
                            class="text-xs font-bold text-red-500 hover:text-red-700 px-2 py-1 bg-red-50 dark:bg-red-900/20 rounded-md transition-colors">Clear</button>
                    @endif
                </div>

                <div class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900/50 flex flex-col">
                    <!-- Order Settings -->
                    <div class="p-4 border-b border-gray-100 dark:border-gray-800 space-y-3 bg-white dark:bg-gray-900">
                        <div class="flex p-1 bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <button wire:click="$set('orderType', 'dine_in')"
                                class="flex-1 py-1.5 text-xs font-bold rounded-md transition-all {{ $orderType === 'dine_in' ? 'bg-white dark:bg-gray-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700' }}">Dine
                                In</button>
                            <button wire:click="$set('orderType', 'takeaway')"
                                class="flex-1 py-1.5 text-xs font-bold rounded-md transition-all {{ $orderType === 'takeaway' ? 'bg-white dark:bg-gray-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700' }}">Takeaway</button>
                        </div>
                        @if ($orderType === 'dine_in')
                            <div x-data="{
                                initTomSelect() {
                                    if (this.ts) this.ts.destroy();
                                    this.ts = new TomSelect(this.$refs.select, {
                                        create: false,
                                        sortField: { field: 'text', direction: 'asc' },
                                        onChange: (value) => {
                                            $wire.set('diningTableId', value);
                                        }
                                    });
                                    this.ts.setValue($wire.get('diningTableId'));
                                }
                            }" x-init="initTomSelect()" wire:ignore>
                                <select x-ref="select"
                                    class="tom-select w-full text-sm rounded-lg border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2">
                                    <option value="">Select Table...</option>
                                    @foreach ($this->diningTables as $table)
                                        <option value="{{ $table->id }}">Table {{ $table->number }}
                                            ({{ $table->capacity }} pax)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div x-data="{ open: false }"
                            class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 text-sm font-semibold text-gray-700 dark:text-gray-300 flex justify-between items-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span>Customer Details (Optional)</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open"
                                class="p-3 bg-white dark:bg-gray-900 space-y-2 border-t border-gray-200 dark:border-gray-700">
                                <input type="text" wire:model="customerName" placeholder="Customer Name..."
                                    class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3">
                                <input type="email" wire:model="customerEmail" placeholder="Email Address..."
                                    class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3">
                                <input type="text" wire:model="customerPhone" placeholder="Phone Number..."
                                    class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3">
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="p-4 space-y-3 flex-1">
                        @forelse($cart as $index => $item)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-3 shadow-sm border border-gray-100 dark:border-gray-700">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex-1 space-y-2">
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white text-sm leading-tight">
                                                {{ $item['name'] }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Rp
                                                {{ number_format($item['price'], 0, ',', '.') }}</p>
                                        </div>
                                        <input type="text" wire:model="cart.{{ $index }}.notes"
                                            placeholder="Item notes..."
                                            class="w-full text-xs rounded border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white py-1.5 px-2">
                                    </div>
                                    <button wire:click="removeCartItem({{ $index }})"
                                        class="text-gray-400 hover:text-red-500 transition-colors p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div
                                    class="flex justify-between items-center mt-3 pt-3 border-t border-gray-50 dark:border-gray-700">
                                    <div
                                        class="flex items-center gap-2 bg-gray-50 dark:bg-gray-900 rounded-lg p-1 border border-gray-100 dark:border-gray-700">
                                        <button
                                            wire:click="updateCartQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                            class="w-7 h-7 flex items-center justify-center rounded bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 shadow-sm hover:text-indigo-600 font-bold">−</button>
                                        <span
                                            class="w-6 text-center text-sm font-bold text-gray-900 dark:text-white">{{ $item['quantity'] }}</span>
                                        <button
                                            wire:click="updateCartQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                            class="w-7 h-7 flex items-center justify-center rounded bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 shadow-sm hover:text-indigo-600 font-bold">+</button>
                                    </div>
                                    <span class="font-extrabold text-sm text-gray-900 dark:text-white">Rp
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-10 opacity-60">
                                <span class="text-4xl mb-2">🛒</span>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cart is empty</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Promo -->
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 mt-auto">
                        <div class="flex gap-2">
                            <input type="text" wire:model="promoCode" placeholder="Promo Code"
                                class="flex-1 text-sm rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white py-2 px-3"
                                @if ($appliedPromo) disabled @endif>
                            @if ($appliedPromo)
                                <button wire:click="removePromo"
                                    class="px-4 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg text-xs font-bold transition-colors">Remove</button>
                            @else
                                <button wire:click="applyPromo"
                                    class="px-4 bg-gray-900 text-white hover:bg-black rounded-lg text-xs font-bold transition-colors">Apply</button>
                            @endif
                        </div>
                    </div>
                </div>

                @php
                    $calc = $this->calculations;
                @endphp

                <!-- Payment Action -->
                <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/30">
                    <div class="space-y-1.5 mb-3">
                        <div class="flex justify-between text-xs text-gray-500 font-medium">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($calc['subtotal'], 0, ',', '.') }}</span>
                        </div>
                        @if ($calc['discountAmount'] > 0)
                            <div class="flex justify-between text-xs text-red-500 font-medium">
                                <span>Discount</span>
                                <span>-Rp {{ number_format($calc['discountAmount'], 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @foreach ($calc['chargesList'] as $charge)
                            <div class="flex justify-between text-xs text-gray-500 font-medium">
                                <span>{{ $charge['name'] }}</span>
                                <span>Rp {{ number_format($charge['amount'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <div
                            class="flex justify-between text-base font-extrabold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-700">
                            <span>Total</span>
                            <span class="text-indigo-600 dark:text-indigo-400">Rp
                                {{ number_format($calc['total'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2" x-data="{
                        paymentMethod: @entangle('paymentMethod'),
                        cashAmount: '',
                        get change() {
                            const val = String(this.cashAmount || '0').replace(/\D/g, '');
                            const amount = parseInt(val) || 0;
                            return Math.max(0, amount - {{ $calc['total'] }});
                        },
                        formatRupiah(number) {
                            return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(number);
                        }
                    }">
                        <div class="flex gap-2 mb-3">
                            <select x-model="paymentMethod"
                                class="flex-1 rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm py-2 px-3 text-gray-900 dark:text-white">
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>

                        <div x-show="paymentMethod === 'cash'" style="display: none;"
                            class="space-y-3 mb-3 border border-gray-200 dark:border-gray-700 rounded-xl p-3 bg-white dark:bg-gray-800">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Uang
                                    Diterima</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium text-sm">Rp</span>
                                    <input type="text" x-model="cashAmount"
                                        @input="cashAmount = formatRupiah($event.target.value.replace(/\D/g, ''))"
                                        class="w-full pl-9 rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm py-2 px-3 text-gray-900 dark:text-white font-bold focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="0">
                                </div>
                            </div>
                            <div
                                class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700">
                                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Kembalian</span>
                                <span class="text-base font-extrabold text-emerald-600 dark:text-emerald-400"
                                    x-text="'Rp ' + formatRupiah(change)">Rp 0</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="createOrder"
                                class="w-full py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-xl font-bold text-sm transition-colors">
                                Save (Unpaid)
                            </button>
                            <button x-on:click="$wire.createOrder(paymentMethod)"
                                class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Process Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
    @include('admin.partials.form-styles')
@endpush

@push('scripts')
    @include('admin.partials.form-scripts')
@endpush
