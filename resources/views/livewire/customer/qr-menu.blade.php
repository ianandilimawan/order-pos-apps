<div>
    {{-- ============================== --}}
    {{-- ORDER SUCCESS --}}
    {{-- ============================== --}}
    @if ($orderSuccess)
        <div
            style="min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;background:#fff;">

            {{-- Check icon --}}
            <div class="anim-pop"
                style="width:80px;height:80px;border-radius:50%;background:#22c55e;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 25px rgba(34,197,94,0.25);">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <h2 class="anim-up anim-d1" style="font-size:22px;font-weight:800;margin-top:20px;">Pesanan Berhasil!</h2>
            <p class="anim-up anim-d1" style="font-size:13px;color:#999;margin-top:6px;">Pesananmu sedang diproses
            </p>

            {{-- Order card --}}
            <div class="anim-up anim-d2"
                style="margin-top:28px;width:100%;max-width:360px;background:#fafaf8;border:1.5px solid #eee;border-radius:16px;padding:20px;">
                <p
                    style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#bbb;text-align:center;">
                    Nomor Pesanan</p>
                <p
                    style="font-size:20px;font-weight:800;text-align:center;margin-top:6px;color:#1a1a1a;letter-spacing:0.5px;">
                    {{ $successOrderNumber }}</p>

                <div style="height:1px;background:#eee;margin:16px 0;"></div>

                {{-- Items --}}
                @foreach ($successOrderItems as $item)
                    <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:13px;">
                        <span style="color:#444;">{{ $item['name'] }}
                            @if (!empty($item['notes']))
                                <br><span style="font-size:11px;color:#888;">Notes: {{ $item['notes'] }}</span>
                            @endif
                            <br><span style="color:#999;">x{{ $item['quantity'] }}</span>
                        </span>
                        <span style="font-weight:600;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                @endforeach

                <div style="height:1px;background:#eee;margin:14px 0;"></div>

                {{-- Subtotal --}}
                <div style="display:flex;justify-content:space-between;padding:3px 0;font-size:13px;color:#888;">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($successOrderSubtotal, 0, ',', '.') }}</span>
                </div>

                @if ($successDiscountAmount > 0)
                    <div
                        style="display:flex;justify-content:space-between;padding:3px 0;font-size:13px;color:#ef4444;font-weight:600;">
                        <span>Diskon Promo</span>
                        <span>- Rp {{ number_format($successDiscountAmount, 0, ',', '.') }}</span>
                    </div>
                @endif

                {{-- Charges --}}
                @foreach ($successOrderCharges as $charge)
                    <div style="display:flex;justify-content:space-between;padding:3px 0;font-size:13px;color:#888;">
                        <span>{{ $charge['name'] }} @if ($charge['type'] == 'percentage')
                                ({{ $charge['rate'] }}%)
                            @endif
                        </span>
                        <span>Rp {{ number_format($charge['amount'], 0, ',', '.') }}</span>
                    </div>
                @endforeach

                <div style="height:1px;background:#eee;margin:10px 0;"></div>

                {{-- Total --}}
                <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:800;">
                    <span>Total</span>
                    <span>Rp {{ number_format($successOrderTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Info --}}
            <div class="anim-up anim-d3"
                style="margin-top:16px;display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f0f9ff;border:1px solid #e0f2fe;border-radius:10px;max-width:360px;width:100%;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"
                    style="flex-shrink:0;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 16v-4"></path>
                    <path d="M12 8h.01"></path>
                </svg>
                <span style="font-size:12px;color:#64748b;">Silahkan menuju kasir untuk pembayaran</span>
            </div>

            {{-- CTA --}}
            <button wire:click="resetOrder" class="anim-up anim-d4"
                style="margin-top:28px;padding:14px 48px;border-radius:14px;font-size:14px;font-weight:700;background:#1a1a1a;color:#fff;border:none;cursor:pointer;font-family:inherit;">
                Pesan Lagi
            </button>
        </div>
    @else
        {{-- ============================== --}}
        {{-- MAIN MENU --}}
        {{-- ============================== --}}

        {{-- Header --}}
        <header class="menu-header sticky top-0 z-40">
            <div style="padding:14px 16px;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h1 style="font-size:18px;font-weight:800;letter-spacing:-0.3px;">
                        {{ config('app.name', 'Cafe Menu') }}</h1>
                    @if ($dining_table)
                        <span
                            style="display:inline-flex;align-items:center;gap:5px;margin-top:4px;padding:3px 10px;background:#f0fdf4;border:1px solid #dcfce7;border-radius:100px;font-size:11px;font-weight:700;color:#16a34a;">
                            <span style="width:5px;height:5px;border-radius:50%;background:#22c55e;"></span>
                            Meja {{ $dining_table->number }}
                        </span>
                    @else
                        <p style="font-size:12px;color:#aaa;margin-top:2px;">Selamat datang 👋</p>
                    @endif
                </div>
                @if (count($cart) > 0)
                    <div style="position:relative;padding:4px;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#666"
                            stroke-width="2">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span
                            style="position:absolute;top:-2px;right:-2px;width:18px;height:18px;border-radius:50%;background:#ef4444;color:#fff;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(239,68,68,0.3);">{{ collect($cart)->sum('quantity') }}</span>
                    </div>
                @endif
            </div>
        </header>

        {{-- Categories --}}
        <div class="sticky top-[54px] z-30" style="background:rgba(250,250,247,0.92);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid rgba(0,0,0,0.06);">
            <div class="cat-scroll hide-scrollbar">
                @foreach ($categories as $category)
                    <button type="button" wire:click="selectCategory({{ $category->id }})"
                        class="cat-pill {{ $activeCategoryId == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Products --}}
        <div class="product-grid" style="padding-bottom:100px;">
            @forelse($products as $product)
                <div class="product-card">
                    <div class="img-wrap">
                        @if($product->best_seller)
                            <div class="best-badge">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                                BEST
                            </div>
                        @endif
                        @if ($product->image)
                            <img src="{{ \App\Services\FileUploadService::getFileUrl($product->image) }}"
                                alt="{{ $product->name }}">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ccc"
                                    stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="info">
                        <h3
                            style="font-size:13px;font-weight:700;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $product->name }}</h3>
                        @if ($product->description)
                            <p
                                style="font-size:11px;color:#aaa;margin-top:2px;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $product->description }}</p>
                        @endif
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:8px;">
                            <span style="font-size:13px;font-weight:800;color:#1a1a1a;">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @php $inCart = collect($cart)->firstWhere('product_id', $product->id); @endphp
                            @if ($inCart)
                                <div class="qty-stepper">
                                    <button type="button"
                                        wire:click="updateQuantity({{ collect($cart)->search(fn($i) => $i['product_id'] == $product->id) }}, -1)">
                                        <svg style="pointer-events:none;" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </button>
                                    <span class="qty-val">{{ $inCart['quantity'] }}</span>
                                    <button type="button" wire:click="addToCart({{ $product->id }})">
                                        <svg style="pointer-events:none;" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <button type="button" wire:click="addToCart({{ $product->id }})" class="add-btn">
                                    <svg style="pointer-events:none;" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:60px 20px;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ddd"
                        stroke-width="1.5" style="margin:0 auto 10px;">
                        <path
                            d="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5m16 0h-2.586a1 1 0 0 0-.707.293l-2.414 2.414a1 1 0 0 1-.707.293h-3.172a1 1 0 0 1-.707-.293l-2.414-2.414A1 1 0 0 0 6.586 13H4">
                        </path>
                    </svg>
                    <p style="font-size:13px;color:#bbb;">Belum ada produk di kategori ini</p>
                </div>
            @endforelse
        </div>

        {{-- ============================== --}}
        {{-- FLOATING CART --}}
        {{-- ============================== --}}
        @if (count($cart) > 0)
            <div class="cart-wrap"
                style="position:fixed;bottom:0;left:0;right:0;padding:14px 16px;z-index:40;background:linear-gradient(to top, #fafaf7, #fafaf7ee, transparent);padding-top:36px;">
                <button type="button" wire:click="$set('showCheckout', true)" class="cart-btn">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span
                            style="background:rgba(255,255,255,0.2);padding:4px 10px;border-radius:8px;font-size:12px;font-weight:700;">{{ collect($cart)->sum('quantity') }}</span>
                        <span style="font-size:14px;font-weight:600;">Lihat Pesanan</span>
                    </div>
                    <span style="font-size:14px;font-weight:800;">Rp
                        {{ number_format($subtotal, 0, ',', '.') }}</span>
                </button>
            </div>
        @endif

        {{-- ============================== --}}
        {{-- CHECKOUT SHEET --}}
        {{-- ============================== --}}
        <div x-data="{ open: @entangle('showCheckout') }">
            {{-- Backdrop --}}
            <div x-show="open" x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="open = false"
                class="sheet-backdrop"></div>
            {{-- Sheet --}}
            <div x-show="open" x-cloak
                x-transition:enter="transition ease-out duration-[400ms]"
                x-transition:enter-start="translate-y-full"
                x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full"
                class="sheet">
                <div class="sheet-handle"></div>

                {{-- Sheet Header --}}
                <div style="padding:16px 20px 12px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
                    <h2 style="font-size:18px;font-weight:800;">Pesananmu</h2>
                    <button type="button" @click="open = false"
                        style="width:32px;height:32px;border-radius:50%;border:none;background:#f3f3ee;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.15s;">
                        <svg style="pointer-events:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#666"
                            stroke-width="2.5" stroke-linecap="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                {{-- Sheet Body (scrollable) --}}
                <div style="overflow-y:auto;padding:0 20px 20px;flex:1;-webkit-overflow-scrolling:touch;">

                    {{-- Type toggle --}}
                    <div class="type-toggle">
                        <button type="button" wire:click="$set('orderType', 'dine_in')"
                            class="{{ $orderType === 'dine_in' ? 'active' : '' }}">Dine In</button>
                        <button type="button" wire:click="$set('orderType', 'take_away')"
                            class="{{ $orderType === 'take_away' ? 'active' : '' }}">Take Away</button>
                    </div>

                    @if ($orderType === 'dine_in' && !$dining_table)
                        <div
                            style="margin-top:12px;padding:10px 14px;background:#fffbeb;border:1px solid #fef3c7;border-radius:12px;display:flex;align-items:center;gap:8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706"
                                stroke-width="2" style="flex-shrink:0;">
                                <path
                                    d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                </path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <span style="font-size:12px;color:#92400e;">Scan QR di meja untuk Dine In, atau pilih Take
                                Away.</span>
                        </div>
                    @endif

                    {{-- Customer info --}}
                    <div style="margin-top:16px;display:flex;flex-direction:column;gap:14px;">
                        <div>
                            <label class="field-label">Nama Kamu</label>
                            <input type="text" wire:model="customerName" class="field-input"
                                placeholder="Masukkan nama (opsional)">
                        </div>
                        <div>
                            <label class="field-label">Email</label>
                            <input type="email" wire:model="customerEmail" class="field-input"
                                placeholder="Email (opsional)">
                        </div>
                        <div>
                            <label class="field-label">No Whatsapp / Telp</label>
                            <input type="text" wire:model="customerPhone" class="field-input"
                                placeholder="Nomor Telp (opsional)">
                        </div>
                    </div>

                    <div style="height:1px;background:#eee;margin:20px 0;"></div>

                    {{-- Cart items --}}
                    <label class="field-label">Item Pesanan</label>
                    <div style="display:flex;flex-direction:column;gap:10px;margin-top:8px;">
                        @foreach ($cart as $index => $item)
                            <div style="background:#f9f9f6;padding:14px;border-radius:14px;border:1px solid rgba(0,0,0,0.06);">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
                                    <div style="flex:1;min-width:0;">
                                        <p style="font-size:14px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item['name'] }}</p>
                                        <p style="font-size:12px;font-weight:600;color:#888;margin-top:2px;">Rp
                                            {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="qty-stepper">
                                        <button type="button" wire:click="updateQuantity({{ $index }}, -1)">
                                            <svg style="pointer-events:none;" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5">
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                        <span class="qty-val">{{ $item['quantity'] }}</span>
                                        <button type="button" wire:click="updateQuantity({{ $index }}, 1)">
                                            <svg style="pointer-events:none;" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div style="margin-top:8px;">
                                    <input type="text" wire:model.blur="cart.{{ $index }}.notes"
                                        placeholder="Catatan item (opsional)..."
                                        style="width:100%;font-size:12px;padding:8px 12px;border:1px solid #e5e5e0;border-radius:10px;background:#fff;outline:none;font-family:inherit;transition:border-color 0.2s;"
                                        onfocus="this.style.borderColor='#1a1a1a'" onblur="this.style.borderColor='#e5e5e0'">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="height:1px;background:#eee;margin:20px 0;"></div>

                    {{-- Promo code --}}
                    <div>
                        <label class="field-label">Kode Promo (Opsional)</label>
                        <div style="display:flex;gap:8px;margin-top:4px;">
                            <input type="text" wire:model="promoCode" class="field-input"
                                placeholder="Masukkan kode promo" @if ($appliedPromo) disabled @endif
                                style="flex:1;">
                            @if ($appliedPromo)
                                <button type="button" wire:click="removePromo"
                                    style="padding:0 16px;border-radius:12px;background:#fee2e2;color:#ef4444;font-weight:700;border:none;cursor:pointer;font-size:13px;font-family:inherit;transition:opacity 0.15s;">Hapus</button>
                            @else
                                <button type="button" wire:click="applyPromo"
                                    style="padding:0 16px;border-radius:12px;background:#1a1a1a;color:#fff;font-weight:700;border:none;cursor:pointer;font-size:13px;font-family:inherit;transition:opacity 0.15s;">Pakai</button>
                            @endif
                        </div>
                    </div>

                    <div style="height:1px;background:#eee;margin:20px 0;"></div>

                    {{-- Order summary --}}
                    <div style="display:flex;flex-direction:column;gap:8px;background:#f9f9f6;padding:16px;border-radius:14px;border:1px solid rgba(0,0,0,0.05);">
                        <div style="display:flex;justify-content:space-between;font-size:13px;color:#999;">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if ($discountAmount > 0)
                            <div
                                style="display:flex;justify-content:space-between;font-size:13px;color:#ef4444;font-weight:600;">
                                <span>Diskon Promo</span>
                                <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @foreach ($this->charges as $charge)
                            <div style="display:flex;justify-content:space-between;font-size:13px;color:#999;">
                                <span>{{ $charge['name'] }} @if ($charge['type'] == 'percentage')
                                        ({{ $charge['rate'] }}%)
                                    @endif
                                </span>
                                <span>Rp {{ number_format($charge['amount'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <div style="height:1px;background:#e5e5e0;margin:4px 0;"></div>
                        <div style="display:flex;justify-content:space-between;font-size:17px;font-weight:800;">
                            <span>Total</span>
                            <span>Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Sheet Footer --}}
                <div style="padding:14px 20px;padding-bottom:max(20px, env(safe-area-inset-bottom, 20px));border-top:1px solid #eee;flex-shrink:0;background:#fff;">
                    <button type="button" wire:click="submitOrder" wire:loading.attr="disabled" class="cta-btn">
                        <span wire:loading.remove wire:target="submitOrder">Pesan Sekarang</span>
                        <span wire:loading wire:target="submitOrder">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2"
                                style="animation:spin 1s linear infinite;display:inline-block;vertical-align:middle;margin-right:6px;">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>

    @endif
</div>
