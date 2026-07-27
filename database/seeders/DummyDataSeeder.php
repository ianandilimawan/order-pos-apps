<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\DiningTable;
use App\Models\ChargeSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderCharge;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        Product::truncate();
        DiningTable::truncate();
        ChargeSetting::truncate();
        Order::truncate();
        OrderItem::truncate();
        OrderCharge::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Categories
        $categories = [
            ['name' => 'Signature Coffee', 'description' => 'Kopi andalan racikan barista kami', 'show' => true, 'sort' => 1],
            ['name' => 'Classic Coffee', 'description' => 'Menu kopi klasik (Espresso Based)', 'show' => true, 'sort' => 2],
            ['name' => 'Non-Coffee', 'description' => 'Minuman segar tanpa kopi', 'show' => true, 'sort' => 3],
            ['name' => 'Main Course', 'description' => 'Makanan berat & Rice Bowl', 'show' => true, 'sort' => 4],
            ['name' => 'Snacks & Pastry', 'description' => 'Cemilan ringan untuk teman ngopi', 'show' => true, 'sort' => 5],
        ];
        
        $catIds = [];
        foreach ($categories as $cat) {
            $cat['slug'] = Str::slug($cat['name']);
            $created = Category::create($cat);
            $catIds[$cat['name']] = $created->id;
        }

        // 2. Products
        $products = [
            // Signature Coffee
            ['category_id' => $catIds['Signature Coffee'], 'name' => 'Es Kopi Susu Aren', 'description' => 'Kopi susu dengan gula aren murni', 'price' => 22000, 'image' => 'product/65d8e2ad58d9e_1785131622_Bq55z4sr.webp', 'is_available' => true, 'show' => true, 'sort' => 1],
            ['category_id' => $catIds['Signature Coffee'], 'name' => 'Caramel Macchiato', 'description' => 'Espresso, susu, dan sirup caramel premium', 'price' => 32000, 'image' => 'product/caramel-macchiato-10_1785131631_vLy8EoSo.webp', 'is_available' => true, 'show' => true, 'sort' => 2],
            ['category_id' => $catIds['Signature Coffee'], 'name' => 'Pandan Coffee Latte', 'description' => 'Kopi susu dengan aroma pandan wangi', 'price' => 25000, 'image' => 'product/pandan-coffee-2-2a353486-da98-4635-beb6-27800000c4c6_1784778985_Od2uoDEl.webp', 'is_available' => true, 'show' => true, 'sort' => 3],
            ['category_id' => $catIds['Signature Coffee'], 'name' => 'Avocado Coffee', 'description' => 'Jus alpukat dicampur dengan espresso dan es krim', 'price' => 35000, 'image' => 'product/resep-coffee-alpukat_1785131691_rrgLjpJo.webp', 'is_available' => true, 'show' => true, 'sort' => 4],
            
            // Classic Coffee
            ['category_id' => $catIds['Classic Coffee'], 'name' => 'Americano (Hot/Ice)', 'description' => 'Double shot espresso dengan air', 'price' => 18000, 'image' => 'product/img-0054_1785131702_Kkvq4QPg.webp', 'is_available' => true, 'show' => true, 'sort' => 5],
            ['category_id' => $catIds['Classic Coffee'], 'name' => 'Caffe Latte (Hot/Ice)', 'description' => 'Espresso dengan susu segar', 'price' => 24000, 'image' => 'product/img-0055_1785131718_fyclUX5R.webp', 'is_available' => true, 'show' => true, 'sort' => 6],
            ['category_id' => $catIds['Classic Coffee'], 'name' => 'Cappuccino (Hot/Ice)', 'description' => 'Espresso dengan busa susu tebal', 'price' => 24000, 'image' => 'product/img-0056-2_1785131735_G8DsU0XX.webp', 'is_available' => true, 'show' => true, 'sort' => 7],
            ['category_id' => $catIds['Classic Coffee'], 'name' => 'Mochaccino (Hot/Ice)', 'description' => 'Campuran kopi, susu, dan cokelat murni', 'price' => 28000, 'image' => 'product/img-0057-2_1785131748_1a6FHqUp.webp', 'is_available' => true, 'show' => true, 'sort' => 8],
            
            // Non-Coffee
            ['category_id' => $catIds['Non-Coffee'], 'name' => 'Matcha Latte', 'description' => 'Premium Japanese matcha dengan susu', 'price' => 26000, 'image' => 'product/img-0058-2_1785131759_UZDrr6LV.webp', 'is_available' => true, 'show' => true, 'sort' => 9],
            ['category_id' => $catIds['Non-Coffee'], 'name' => 'Taro Latte', 'description' => 'Susu segar dengan rasa taro', 'price' => 24000, 'image' => 'product/img-0060_1785131766_19BuxI4Z.webp', 'is_available' => true, 'show' => true, 'sort' => 10],
            ['category_id' => $catIds['Non-Coffee'], 'name' => 'Lychee Yakult', 'description' => 'Minuman segar yakult dengan sirup leci dan selasih', 'price' => 22000, 'image' => 'product/img-0061_1785131775_yEgUejXb.webp', 'is_available' => true, 'show' => true, 'sort' => 11],
            ['category_id' => $catIds['Non-Coffee'], 'name' => 'Strawberry Mojito', 'description' => 'Mojito segar tanpa alkohol rasa strawberry', 'price' => 25000, 'image' => 'product/img-0062_1785131785_7SqwGttU.webp', 'is_available' => true, 'show' => true, 'sort' => 12],
            
            // Main Course
            ['category_id' => $catIds['Main Course'], 'name' => 'Nasi Goreng Spesial Cafe', 'description' => 'Nasi goreng dengan telur, sosis, dan ayam suwir', 'price' => 30000, 'image' => 'product/img-0063_1785131797_v5UZpA6r.webp', 'is_available' => true, 'show' => true, 'sort' => 13],
            ['category_id' => $catIds['Main Course'], 'name' => 'Chicken Katsu Curry', 'description' => 'Nasi kari khas Jepang dengan ayam katsu krispi', 'price' => 42000, 'image' => 'product/img-0064_1785131814_6lucNtd3.webp', 'is_available' => true, 'show' => true, 'sort' => 14],
            ['category_id' => $catIds['Main Course'], 'name' => 'Spaghetti Aglio Olio', 'description' => 'Pasta pedas gurih dengan topping smoked beef', 'price' => 38000, 'image' => 'product/img-0066_1785131830_OnEE3som.webp', 'is_available' => true, 'show' => true, 'sort' => 15],
            ['category_id' => $catIds['Main Course'], 'name' => 'Beef Teriyaki Rice Bowl', 'description' => 'Daging sapi tumis teriyaki dengan telur mata sapi', 'price' => 45000, 'image' => 'product/img-0067_1785131897_2p4tGRap.webp', 'is_available' => true, 'show' => true, 'sort' => 16],
            
            // Snacks & Pastry
            ['category_id' => $catIds['Snacks & Pastry'], 'name' => 'French Fries', 'description' => 'Kentang goreng gurih porsi besar', 'price' => 18000, 'image' => 'product/img-0068_1785131858_wWq76QFy.webp', 'is_available' => true, 'show' => true, 'sort' => 17],
            ['category_id' => $catIds['Snacks & Pastry'], 'name' => 'Mix Platter', 'description' => 'Sosis, kentang, chicken nugget, dan onion ring', 'price' => 35000, 'image' => 'product/img-0069_1785131871_KoldJkDO.webp', 'is_available' => true, 'show' => true, 'sort' => 18],
            ['category_id' => $catIds['Snacks & Pastry'], 'name' => 'Butter Croissant', 'description' => 'Croissant mentega klasik yang renyah', 'price' => 22000, 'image' => 'product/img-0070_1785131846_JxOIdTGm.webp', 'is_available' => true, 'show' => true, 'sort' => 19],
            ['category_id' => $catIds['Snacks & Pastry'], 'name' => 'Cireng Bumbu Rujak', 'description' => 'Cireng krispi dengan cocolan bumbu rujak manis pedas', 'price' => 20000, 'image' => 'product/img-0071_1785131888_rQJdhPxt.webp', 'is_available' => true, 'show' => true, 'sort' => 20],
        ];
        
        $prodModels = [];
        foreach ($products as $prod) {
            $prod['slug'] = Str::slug($prod['name']);
            $prodModels[] = Product::create($prod);
        }

        // 3. Dining Tables (15 Tables)
        for ($i = 1; $i <= 15; $i++) {
            DiningTable::create([
                'number' => strval($i),
                'capacity' => $i <= 5 ? 2 : ($i <= 12 ? 4 : 8), // VIP/Large tables for 13,14,15
                'status' => 'available'
            ]);
        }
        
        $tables = DiningTable::all();

        // 4. Charge Settings
        $charges = [
            ['name' => 'PB1 (Pajak Restoran)', 'type' => 'percentage', 'value' => 10.00, 'applies_to' => 'all', 'is_active' => true, 'sort' => 1],
            ['name' => 'Service Charge', 'type' => 'percentage', 'value' => 5.00, 'applies_to' => 'dine_in', 'is_active' => true, 'sort' => 2],
            ['name' => 'Packaging Fee', 'type' => 'fixed', 'value' => 3000.00, 'applies_to' => 'take_away', 'is_active' => true, 'sort' => 3],
        ];

        foreach ($charges as $charge) {
            ChargeSetting::create($charge);
        }

        // 5. Dummy Orders (To populate Kasir and history)
        $statuses = ['pending', 'confirmed', 'preparing', 'completed', 'completed'];
        
        for ($i = 1; $i <= 8; $i++) {
            $isDineIn = $i % 3 !== 0; // mostly dine in
            $status = $statuses[array_rand($statuses)];
            $isPaid = $status == 'completed';
            
            $subtotal = 0;
            $orderItems = [];
            
            // Random 1 to 4 products
            $orderedProducts = collect($prodModels)->random(rand(1, 4));
            foreach ($orderedProducts as $op) {
                $qty = rand(1, 3);
                $st = $op->price * $qty;
                $subtotal += $st;
                
                $orderItems[] = [
                    'product_id' => $op->id,
                    'quantity' => $qty,
                    'price' => $op->price,
                    'subtotal' => $st
                ];
            }
            
            // Calculate total
            $tax = $subtotal * 0.1;
            $serviceOrPack = $isDineIn ? ($subtotal * 0.05) : 3000;
            $total = $subtotal + $tax + $serviceOrPack;

            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'dining_table_id' => $isDineIn ? $tables->random()->id : null,
                'order_type' => $isDineIn ? 'dine_in' : 'take_away',
                'status' => $status,
                'payment_status' => $isPaid ? 'paid' : 'unpaid',
                'payment_method' => $isPaid ? ['cash', 'qris', 'transfer'][array_rand(['cash', 'qris', 'transfer'])] : null,
                'paid_at' => $isPaid ? now()->subMinutes(rand(1, 60)) : null,
                'subtotal' => $subtotal,
                'total' => $total,
                'notes' => $isDineIn ? "Meja $i pesanan" : "Atas nama: Budi",
                'created_at' => now()->subMinutes(rand(1, 120))
            ]);

            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }
            
            OrderCharge::create([
                'order_id' => $order->id,
                'charge_setting_id' => 1,
                'charge_name' => 'PB1 (Pajak Restoran)',
                'charge_type' => 'percentage',
                'charge_rate' => 10,
                'charge_amount' => $tax,
            ]);
            
            OrderCharge::create([
                'order_id' => $order->id,
                'charge_setting_id' => $isDineIn ? 2 : 3,
                'charge_name' => $isDineIn ? 'Service Charge' : 'Packaging Fee',
                'charge_type' => $isDineIn ? 'percentage' : 'fixed',
                'charge_rate' => $isDineIn ? 5 : 3000,
                'charge_amount' => $serviceOrPack,
            ]);
        }
    }
}
