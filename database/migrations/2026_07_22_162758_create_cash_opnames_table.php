<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cash_opnames', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');
            $table->date('opname_date');
            $table->bigInteger('expected_cash');
            $table->bigInteger('actual_cash');
            $table->bigInteger('expected_qris');
            $table->bigInteger('actual_qris');
            $table->bigInteger('expected_transfer');
            $table->bigInteger('actual_transfer');
            $table->bigInteger('difference');
            $table->string('status');
            $table->text('notes');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_opnames');
    }
};
