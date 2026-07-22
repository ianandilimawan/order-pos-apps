<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_charges', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('charge_setting_id')->nullable()->constrained('charge_settings')->nullOnDelete();
            $table->string('charge_name');
            $table->enum('charge_type', ['percentage', 'fixed'])->default('percentage');
            $table->bigInteger('charge_rate')->default(0);
            $table->bigInteger('charge_amount')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_charges');
    }
};
