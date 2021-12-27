<?php

namespace Database\Seeders;

use App\Models\Product\ProductVariant;
use App\Models\Product\Warehouse;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User\User::factory(10)->create();
        // ProductVariant::factory(10)->create();
        Warehouse::factory(10)->create();
    }
}
