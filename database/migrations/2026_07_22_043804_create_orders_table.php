<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('order_number')->unique();
            $table->foreignId('dining_table_id')->nullable()->constrained('dining_tables')->nullOnDelete();
            $table->enum('order_type', ['dine_in', 'take_away'])->default('dine_in');
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'qris', 'transfer'])->nullable();
            $table->bigInteger('subtotal')->default(0);
            $table->bigInteger('total')->default(0);
            $table->text('notes')->nullable();
            $table->datetime('paid_at')->nullable();

            $table->index('order_number');
            $table->index('status');
            $table->index('payment_status');
            $table->index('order_type');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
