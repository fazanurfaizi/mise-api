<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory()->create()->id,
            'name' => $this->faker->word
        ];
    }
}
