<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true)->unique();
            $table->integer('user_id');
            $table->string('invoice_no');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            // shipping details
            $table->string('shipping_first_name');
            $table->string('shipping_last_name');
            $table->string('shipping_phone_number');
            $table->string('shipping_telephone_number')->nullable();
            $table->string('shipping_company')->nullable();
            $table->string('shipping_address');
            $table->string('shipping_street');
            $table->string('shipping_zip_code');
            $table->string('shipping_country');

            $table->string('status');
            $table->dateTime('ordered_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->decimal('total');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
