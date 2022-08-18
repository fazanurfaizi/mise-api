<?php

namespace Database\Factories\Product;

use App\Models\Product\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->words(rand(1, 4), true);

        return [
            'name' => $this->faker->unique()->words(rand(1, 4), true),
            'slug' => Str::slug($title),
            'description' => $this->faker->sentence,
            'parent_id' => null
        ];
    }
}
