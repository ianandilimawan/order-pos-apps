<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\ChargeSetting;
use App\Models\Promo;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;

class Pos extends Component
{
    public $mode = 'list'; // 'list' or 'create'

    // LIST MODE PROPERTIES
    public $orders = [];
    public $selectedOrder = null;

    // CREATE MODE PROPERTIES
    public $cart = [];
    public $search = '';
    public $selectedCategoryId = null;
    public $orderType = 'dine_in';
    public $diningTableId = null;
    public $paymentMethod = 'cash';
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    public $notes = '';
    public $promoCode = '';
    public $appliedPromo = null;

    public function mount()
    {
        $this->loadOrders();
    }

    public function switchMode($newMode)
    {
        $this->mode = $newMode;
        if ($newMode == 'list') {
            $this->loadOrders();
        }
    }

    // --- LIST MODE METHODS ---

    public function loadOrders()
    {
        // Get active orders (not completed/cancelled) for today only
        $this->orders = Order::with(['diningTable', 'items.product', 'charges'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = Order::with(['diningTable', 'items.product', 'charges'])->find($orderId);
    }

    public function updateOrderStatus($status)
    {
        if (!$this->selectedOrder) return;
        
        $oldValues = $this->selectedOrder->getAttributes();
        $this->selectedOrder->update([
            'status' => $status,
            'user_id' => auth()->id() // Link to the kasir handling this
        ]);
        
        ActivityLogService::logUpdate($this->selectedOrder, $oldValues, "Updated order status to {$status}");
        
        $this->dispatch('notify', type: 'success', message: 'Status updated to ' . ucfirst($status));
        $this->loadOrders();
        $this->selectOrder($this->selectedOrder->id);
    }

    public function markAsPaid($paymentMethod)
    {
        if (!$this->selectedOrder) return;
        
        $oldValues = $this->selectedOrder->getAttributes();
        
        $this->selectedOrder->update([
            'payment_status' => 'paid',
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
            'status' => 'completed', // usually completes when paid
            'user_id' => auth()->id() // Link to the kasir handling the payment
        ]);
        
        ActivityLogService::logUpdate($this->selectedOrder, $oldValues, "Processed {$paymentMethod} payment for order");
        
        $this->dispatch('notify', type: 'success', message: 'Payment successful!');
        
        // Keep the order selected so the user can print the receipt
        $this->selectOrder($this->selectedOrder->id);
        $this->loadOrders();
    }

    // --- CREATE MODE METHODS ---

    public function getCategoriesProperty()
    {
        return Category::orderBy('sort')->get();
    }

    public function getProductsProperty()
    {
        $query = Product::where('is_available', true)->where('show', true);
        if ($this->selectedCategoryId) {
            $query->where('category_id', $this->selectedCategoryId);
        }
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        return $query->get();
    }

    public function getDiningTablesProperty()
    {
        return DiningTable::all();
    }

    public function selectCategory($id)
    {
        $this->selectedCategoryId = $id;
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        $index = collect($this->cart)->search(function ($item) use ($productId) {
            return $item['product_id'] == $productId && empty($item['notes']); // Group only if no notes
        });

        if ($index !== false) {
            $this->cart[$index]['quantity']++;
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'notes' => ''
            ];
        }
        
        $this->dispatch('notify', type: 'success', message: 'Ditambahkan ke keranjang');
    }

    public function updateCartQuantity($index, $qty)
    {
        if ($qty < 1) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart); // reindex
        } else {
            $this->cart[$index]['quantity'] = $qty;
        }
    }

    public function removeCartItem($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->notes = '';
        $this->customerName = '';
        $this->customerEmail = '';
        $this->customerPhone = '';
        $this->promoCode = '';
        $this->appliedPromo = null;
    }

    public function applyPromo()
    {
        if (empty($this->promoCode)) return;

        $promo = Promo::where('code', $this->promoCode)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            })->first();

        if (!$promo) {
            $this->dispatch('promo-alert', type: 'error', message: 'Promo tidak valid atau kedaluwarsa');
            return;
        }

        $subtotal = collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        if ($promo->min_purchase && $subtotal < $promo->min_purchase) {
            $this->dispatch('promo-alert', type: 'error', message: 'Minimum pembelian tidak terpenuhi (Rp ' . number_format($promo->min_purchase, 0, ',', '.') . ')');
            return;
        }

        $this->appliedPromo = $promo->toArray();
        $this->dispatch('promo-alert', type: 'success', message: 'Promo berhasil digunakan');
    }

    public function removePromo()
    {
        $this->appliedPromo = null;
        $this->promoCode = '';
    }

    public function getCalculationsProperty()
    {
        $subtotal = collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $discountAmount = 0;

        if ($this->appliedPromo) {
            if ($this->appliedPromo['min_purchase'] && $subtotal < $this->appliedPromo['min_purchase']) {
                $this->removePromo();
            } else {
                if ($this->appliedPromo['type'] === 'percentage') {
                    $discountAmount = $subtotal * ($this->appliedPromo['value'] / 100);
                    if (!empty($this->appliedPromo['max_discount']) && $discountAmount > $this->appliedPromo['max_discount']) {
                        $discountAmount = $this->appliedPromo['max_discount'];
                    }
                } else {
                    $discountAmount = $this->appliedPromo['value'];
                }
            }
        }

        $subtotalAfterDiscount = max(0, $subtotal - $discountAmount);
        
        $charges = ChargeSetting::where('is_active', true)
            ->whereIn('applies_to', ['all', $this->orderType])
            ->orderBy('sort')
            ->get();
        $totalCharges = 0;
        $chargesList = [];

        foreach ($charges as $charge) {
            $amount = $charge->type === 'percentage' 
                ? $subtotalAfterDiscount * ($charge->value / 100)
                : $charge->value;
            
            $totalCharges += $amount;
            $chargesList[] = [
                'name' => $charge->name,
                'type' => $charge->type,
                'rate' => $charge->value,
                'amount' => $amount
            ];
        }

        $total = $subtotalAfterDiscount + $totalCharges;

        return [
            'subtotal' => $subtotal,
            'discountAmount' => $discountAmount,
            'chargesList' => $chargesList,
            'totalCharges' => $totalCharges,
            'total' => $total
        ];
    }

    public function createOrder($paymentMethod = null)
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', type: 'error', message: 'Keranjang kosong');
            return;
        }

        if ($this->orderType == 'dine_in' && !$this->diningTableId) {
            $this->dispatch('notify', type: 'error', message: 'Pilih meja untuk Dine In');
            return;
        }

        DB::beginTransaction();
        try {
            $calc = $this->getCalculationsProperty();

            // Create Order
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5)),
                'order_type' => $this->orderType,
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'dining_table_id' => $this->orderType == 'dine_in' ? $this->diningTableId : null,
                'subtotal' => $calc['subtotal'],
                'promo_id' => $this->appliedPromo ? $this->appliedPromo['id'] : null,
                'discount_amount' => $calc['discountAmount'],
                'total' => $calc['total'],
                'status' => $paymentMethod ? 'completed' : 'pending',
                'payment_status' => $paymentMethod ? 'paid' : 'unpaid',
                'payment_method' => $paymentMethod,
                'paid_at' => $paymentMethod ? now() : null,
                'notes' => $this->notes,
                'user_id' => auth()->id(), // kasir
            ]);

            // Create Items
            foreach ($this->cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'notes' => $item['notes'] ?? null
                ]);
            }

            // Create Charges
            foreach ($calc['chargesList'] as $charge) {
                $order->charges()->create([
                    'charge_name' => $charge['name'],
                    'charge_type' => $charge['type'],
                    'charge_rate' => $charge['rate'],
                    'charge_amount' => $charge['amount'],
                ]);
            }

            DB::commit();

            ActivityLogService::logCreate($order, "Kasir created new order");

            $this->clearCart();
            $this->dispatch('notify', type: 'success', message: 'Pesanan berhasil dibuat!');
            
            // Switch back to list and select the order
            $this->switchMode('list');
            $this->selectOrder($order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', type: 'error', message: 'Failed to create order: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.pos')
            ->extends('admin.layouts.app')
            ->section('content');
    }
}
