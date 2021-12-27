<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
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
            'product_category_id' => ProductCategory::factory()->create()->id,
            'name' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->sentence(10, true),
            // 'is_active' => true
        ];
    }
}
