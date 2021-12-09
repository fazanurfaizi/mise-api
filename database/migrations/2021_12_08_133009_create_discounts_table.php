<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 255)->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['product', 'shipping']);
            $table->enum('discount_type', ['fixed', 'percent']);
            $table->float('discount_value');
            $table->time('time_start');
            $table->time('time_end');
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->boolean('active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
