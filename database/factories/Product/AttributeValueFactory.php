<?php

namespace Database\Factories\Product;

use App\Models\Product\Attribute;
use App\Models\Product\AttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeValueFactory extends Factory
{
    protected $model = AttributeValue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'attribute_id' => Attribute::factory()->create()->id,
            'name' => $this->faker->word,
        ];
    }
}
