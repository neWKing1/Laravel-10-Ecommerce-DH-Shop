<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->double('total');
            $table->string('payment_method');
            $table->text('order_address');
            $table->text('order_phone');
            $table->unsignedBigInteger('order_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
