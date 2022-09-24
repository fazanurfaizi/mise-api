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
        Schema::create('catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->enum('type', ['manual', 'auto']);
            $table->integer('sort')->unsigned()->index()->comment("'1: best_selling', '2: alpha_asc', '3: alpha_desc', '4: price_desc', '5: price_asc', '6: created_desc', '7: created_asc', '8: manual'");
            $table->enum('match_conditions', ['all', 'any'])->default('all');
            $table->dateTime('published_at')->default(now());
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
        Schema::dropIfExists('catalogs');
    }
};
