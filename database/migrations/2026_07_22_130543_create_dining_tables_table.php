<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dining_tables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('deleted_at')->nullable();
            $table->string('number');
            $table->integer('capacity');
            $table->string('status');
            $table->string('qr_code')->nullable();
            $table->boolean('show');

            // Add indexes for better query performance
            $table->index('show');
            $table->index('deleted_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dining_tables');
    }
};
