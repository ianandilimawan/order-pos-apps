<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('show')->default(true);

            $table->index('sort');
            $table->index('show');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
