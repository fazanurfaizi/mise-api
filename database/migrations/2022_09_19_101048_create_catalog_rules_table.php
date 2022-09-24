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
        Schema::create('catalog_rules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('catalog_id')->unsigned();
            $table->integer('rule')->unsigned()->index()->comment("'1: product_title', '2: product_price', '3: compare_at_price', '4: inventory_stock', '5: product_brand', '6: product_category'");
            $table->integer('operator')->unsigned()->index()->comment("'1: equals_to', '2: not_equals_to', '3: less_than', '4: greater_than', '5: starts_with', '6: ends_with', '7: contains', '8: not_contains'");
            $table->string('value');
            $table->timestamps();

            $table->foreign('catalog_id')->references('id')->on('catalogs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_rules');
    }
};
