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
        Schema::create('users_products', function (Blueprint $table) {
            // $table->id();
            $table->integer('users_id');
            $table->integer('products_id');
            // $table->timestamps();

            // $table->foreignId('users_id')
            //     ->references('id')
            //     ->on('users')
            //     ->cascadeOnDelete();

            // $table->foreignId('products_id')
            //     ->references('id')
            //     ->on('products')
            //     ->cascadeOnDelete();

            // $table->foreignId('users_id')
            //     ->constrained('users')
            //     ->cascadeOnDelete();

            // $table->foreignId('products_id')
            //     ->constrained('products')
            //     ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_products');
    }
};
