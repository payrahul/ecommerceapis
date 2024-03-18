<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wish_cart_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('wish_id')->nullable();
            $table->integer('cart_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->tinyInteger('user_id');
            $table->boolean('wco_isActive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wish_cart_orders');
    }
};
