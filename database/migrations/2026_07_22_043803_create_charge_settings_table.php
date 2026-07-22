<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charge_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->bigInteger('value')->default(0);
            $table->enum('applies_to', ['all', 'dine_in', 'take_away'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->integer('sort')->default(0);

            $table->index('is_active');
            $table->index('applies_to');
            $table->index('sort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charge_settings');
    }
};
