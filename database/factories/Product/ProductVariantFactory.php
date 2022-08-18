<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductSku;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductAttribute;
use App\Models\Product\ProductAttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory()->create()->id,
            'product_sku_id' => ProductSku::factory()->create()->id,
            'attribute_id' => ProductAttribute::factory()->create()->id,
            'attribute_value_id' => ProductAttributeValue::factory()->create()->id,
        ];
    }
}
