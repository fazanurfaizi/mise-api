<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use App\Models\Product\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

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
