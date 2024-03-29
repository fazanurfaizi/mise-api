<?php

namespace Database\Factories\Product;

use App\Models\Product\Brand;
use App\Models\Product\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->words(rand(1, 3), true);
        return [
            'name' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->sentence(10, true),
            'brand_id' => Brand::factory()->create()->id,
            'condition' => $this->faker->randomElement(['new', 'second']),
            'min_purchase' => $this->faker->randomDigit(),
            'featured' => $this->faker->boolean()
        ];
    }
}
