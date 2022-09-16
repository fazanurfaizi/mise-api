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
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('email')->unique()->after('description');
            $table->text('address')->after('email');
            $table->string('city')->after('address');
            $table->string('zipcode')->after('city');
            $table->string('phone_number')->nullable()->after('zipcode');
            $table->boolean('is_default')->default(false)->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('zipcode');
            $table->dropColumn('phone_number');
            $table->dropColumn('is_default');
        });
    }
};
