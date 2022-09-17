<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductSku;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductSkuFactory extends Factory
{
    protected $model = ProductSku::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory()->create()->id,
            'code' => Str::random(10),
            'price' => $this->faker->randomFloat(2, 1000, 100000),
            'cost' => $this->faker->randomFloat(2, 1000, 100000)
        ];
    }
}
