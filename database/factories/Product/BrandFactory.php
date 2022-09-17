<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'website' => $this->faker->url(),
            'is_enabled' => $this->faker->boolean()
        ];
    }

    /**
     * Indicate that the model's is_enabled should be false.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function notEnabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_enabled' => false,
            ];
        });
    }
}
