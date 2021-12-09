<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('warehouse_id')->unsigned();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->bigInteger('product_sku_id')->unsigned();
            $table->foreign('product_sku_id')->references('id')->on('product_skus')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->string('aisle')->nullable();
            $table->string('row')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['product_sku_id', 'warehouse_id'], 'product_warehouse_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_stocks');
    }
}
