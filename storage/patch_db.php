<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

if (!Schema::hasTable('promos')) {
    Schema::create('promos', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->text('description')->nullable();
        $table->enum('type', ['percentage', 'fixed'])->default('fixed');
        $table->decimal('value', 15, 2);
        $table->decimal('min_purchase', 15, 2)->default(0);
        $table->decimal('max_discount', 15, 2)->nullable();
        $table->dateTime('valid_until')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    echo "Created promos table.\n";
}
if (!Schema::hasColumn('orders', 'promo_id')) {
    Schema::table('orders', function (Blueprint $table) {
        $table->foreignId('promo_id')->nullable()->constrained('promos')->nullOnDelete();
        $table->decimal('discount_amount', 15, 2)->default(0)->after('subtotal');
    });
    echo "Updated orders table.\n";
}
try {
    DB::table('migrations')->insert([
        ['migration' => '2026_07_22_224446_create_promos_table', 'batch' => 2],
        ['migration' => '2026_07_22_224514_add_promo_fields_to_orders_table', 'batch' => 2],
    ]);
} catch (\Exception $e) {}
