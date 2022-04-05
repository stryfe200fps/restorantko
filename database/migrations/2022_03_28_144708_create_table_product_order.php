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
        Schema::create('order_rows', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('order_id');
            // $table->foreignId('product_id')->constrained();
            $table->string('name');
            $table->decimal('price');
            $table->integer('quantity');
            // $table->string('allergen');
            // $table->foreignId('order_id')->constrained();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_product_order');
    }
};
