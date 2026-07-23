<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderCharge;
use App\Models\ChargeSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class QrMenu extends Component
{
    public $table_id;
    public $dining_table;
    public $categories = [];
    public $products = [];
    public $activeCategoryId = null;
    
    // Cart state
    public $cart = [];
    public $subtotal = 0;
    
    // Checkout state
    public $showCheckout = false;
    public $orderType = 'dine_in'; // dine_in, take_away
    public $notes = '';

    // Promo state
    public $promoCode = '';
    public $appliedPromo = null;
    public $discountAmount = 0;
    
    // Order success state
    public $orderSuccess = false;
    public $successOrderNumber = '';
    public $successOrderTotal = 0;
    public $successOrderSubtotal = 0;
    public $successOrderItems = [];
    public $successOrderCharges = [];
    public $successDiscountAmount = 0;
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';

    public function mount()
    {
        $this->table_id = request()->query('table');
        if ($this->table_id) {
            $this->dining_table = DiningTable::find($this->table_id);
        }
        
        $this->categories = Category::where('show', true)->orderBy('sort')->get();
        if ($this->categories->count() > 0) {
            $this->activeCategoryId = $this->categories->first()->id;
        }
        
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $query = Product::where('show', true)->where('is_available', true)->orderBy('sort');
        
        if ($this->activeCategoryId) {
            $query->where('category_id', $this->activeCategoryId);
        }
        
        $this->products = $query->get();
    }

    public function selectCategory($categoryId)
    {
        $this->activeCategoryId = $categoryId;
        $this->loadProducts();
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        $found = false;
        foreach ($this->cart as $key => $item) {
            // If they have the exact same product and no special notes yet, we could just group them.
            // But since they might want different notes for different items, we still group by default.
            if ($item['product_id'] == $productId) {
                $this->cart[$key]['quantity']++;
                $this->cart[$key]['subtotal'] = $this->cart[$key]['quantity'] * $this->cart[$key]['price'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'subtotal' => $product->price,
                'image' => $product->image,
                'notes' => '',
            ];
        }

        $this->calculateCart();
    }

    public function updateQuantity($index, $change)
    {
        if (!isset($this->cart[$index])) return;
        
        $this->cart[$index]['quantity'] += $change;
        
        if ($this->cart[$index]['quantity'] <= 0) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart); // re-index
        } else {
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['price'];
        }
        
        $this->calculateCart();
    }

    public function updateItemNote($index, $note)
    {
        if (isset($this->cart[$index])) {
            $this->cart[$index]['notes'] = $note;
        }
    }

    public function applyPromo()
    {
        if (empty($this->promoCode)) {
            $this->removePromo();
            return;
        }

        $promo = \App\Models\Promo::where('code', $this->promoCode)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>', now());
            })->first();

        if (!$promo) {
            $this->dispatch('notify', type: 'error', message: 'Kode promo tidak valid atau sudah kadaluarsa.');
            $this->removePromo();
            return;
        }

        if ($this->subtotal < $promo->min_purchase) {
            $this->dispatch('notify', type: 'error', message: 'Minimum pembelian untuk promo ini adalah Rp ' . number_format($promo->min_purchase, 0, ',', '.'));
            $this->removePromo();
            return;
        }

        $this->appliedPromo = $promo;
        $this->calculateCart();
        $this->dispatch('notify', type: 'success', message: 'Promo berhasil digunakan!');
    }

    public function removePromo()
    {
        $this->appliedPromo = null;
        $this->promoCode = '';
        $this->calculateCart();
    }

    public function calculateCart()
    {
        $this->subtotal = collect($this->cart)->sum('subtotal');
        
        // Calculate Discount
        $this->discountAmount = 0;
        if ($this->appliedPromo) {
            if ($this->subtotal < $this->appliedPromo->min_purchase) {
                $this->removePromo(); // invalidates if subtotal drops
            } else {
                if ($this->appliedPromo->type == 'percentage') {
                    $discount = ($this->subtotal * $this->appliedPromo->value) / 100;
                    if ($this->appliedPromo->max_discount && $discount > $this->appliedPromo->max_discount) {
                        $discount = $this->appliedPromo->max_discount;
                    }
                    $this->discountAmount = $discount;
                } else {
                    $this->discountAmount = $this->appliedPromo->value;
                }
            }
        }
    }

    public function getChargesProperty()
    {
        $applicableSettings = ChargeSetting::where('is_active', true)
            ->whereIn('applies_to', ['all', $this->orderType])
            ->orderBy('sort')
            ->get();
            
        $charges = [];
        // Charges apply on subtotal minus discount
        $runningTotal = max(0, $this->subtotal - $this->discountAmount);
        
        foreach ($applicableSettings as $setting) {
            $amount = 0;
            if ($setting->type === 'percentage') {
                $amount = ($runningTotal * $setting->value) / 100;
            } else {
                $amount = $setting->value;
            }
            
            $charges[] = [
                'setting_id' => $setting->id,
                'name' => $setting->name,
                'type' => $setting->type,
                'rate' => $setting->value,
                'amount' => $amount
            ];
        }
        
        return collect($charges);
    }

    public function getTotalProperty()
    {
        return max(0, $this->subtotal - $this->discountAmount) + $this->charges->sum('amount');
    }

    public function submitOrder()
    {
        if (empty($this->cart)) return;
        
        // Validation
        if ($this->orderType === 'dine_in' && !$this->dining_table) {
            // Need a table for dine-in
            $this->dispatch('notify', type: 'error', message: 'Silakan scan QR Code di meja Anda untuk Dine In.');
            return;
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'dining_table_id' => $this->orderType === 'dine_in' ? $this->dining_table?->id : null,
                'order_type' => $this->orderType,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'subtotal' => $this->subtotal,
                'total' => $this->total,
                'promo_id' => $this->appliedPromo ? $this->appliedPromo->id : null,
                'discount_amount' => $this->discountAmount,
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'notes' => '',
            ]);

            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }
            
            foreach ($this->charges as $charge) {
                OrderCharge::create([
                    'order_id' => $order->id,
                    'charge_setting_id' => $charge['setting_id'],
                    'charge_name' => $charge['name'],
                    'charge_type' => $charge['type'],
                    'charge_rate' => $charge['rate'],
                    'charge_amount' => $charge['amount'],
                ]);
            }

            DB::commit();

            // Store success data before clearing
            $this->successOrderNumber = $order->order_number;
            $this->successOrderSubtotal = $this->subtotal;
            $this->successOrderTotal = $this->total;
            $this->successDiscountAmount = $this->discountAmount;
            $this->successOrderItems = $this->cart;
            $this->successOrderCharges = $this->charges->toArray();

            $this->cart = [];
            $this->appliedPromo = null;
            $this->promoCode = '';
            $this->discountAmount = 0;
            $this->showCheckout = false;
            $this->calculateCart();
            $this->orderSuccess = true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', type: 'error', message: 'Gagal membuat pesanan. Silakan coba lagi.');
        }
    }

    public function resetOrder()
    {
        $this->orderSuccess = false;
        $this->successOrderNumber = '';
        $this->successOrderTotal = 0;
        $this->successOrderSubtotal = 0;
        $this->successDiscountAmount = 0;
        $this->successOrderItems = [];
        $this->successOrderCharges = [];
        $this->customerName = '';
        $this->customerEmail = '';
        $this->customerPhone = '';
    }

    public function render()
    {
        return view('livewire.customer.qr-menu')->layout('layouts.customer');
    }
}
