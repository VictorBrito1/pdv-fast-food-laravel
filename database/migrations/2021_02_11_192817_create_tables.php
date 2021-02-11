<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('payment_type')->nullable();
            $table->string('client_name')->nullable();
            $table->float('price')->default(0);
            $table->float('total_paid')->default(0);
            $table->float('change')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price')->default(0);
            $table->timestamps();
        });

        Schema::create('orders_products', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('product_id')->constrained('products');
            $table->unsignedInteger('amount')->default(1);
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('orders_products');
    }
}
