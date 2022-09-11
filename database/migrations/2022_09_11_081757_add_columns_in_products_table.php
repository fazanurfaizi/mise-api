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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('condition', ['new', 'second'])->nullable()->default('new')->after('description');
            $table->integer('min_purchase')->unsigned()->default(1)->after('condition');
            $table->boolean('featured')->nullable()->default(false)->after('min_purchase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('condition');
            $table->dropColumn('min_purchase');
            $table->dropColumn('featured');
        });
    }
};
