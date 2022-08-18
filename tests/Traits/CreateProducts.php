<?php

namespace Tests\Traits;

use App\Models\Product\Product;
use App\Models\Product\ProductAttribute;
use Illuminate\Support\Str;

trait CreateProducts
{
    /**
     * Create a test product with variation
     */
    protected function createTestProduct()
    {
        $product = Product::factory(10)->create();

        $sizeAttribute = ProductAttribute::factory()->make([
            'name' => 'size'
        ]);
        $sizeTerms = ['small', 'medium', 'large'];

        $colorAttribute = ProductAttribute::factory()->make([
            'name' => 'color'
        ]);
        $colorTerms = ['black', 'white'];

        // Set the terms and attributes
        $product->addAttribute($sizeAttribute->name);
        $product->addAttributeTerm($sizeAttribute->name, $sizeTerms);

        $product->addAttribute($colorAttribute->name);
        $product->addAttributeTerm($colorAttribute->name, $colorTerms);

        $variantSmallBlack = [
            'code' => Str::random(16),
            'price' => rand(100, 3000),
            'cost' => rand(100, 3000),
            'variant' => [
                ['option' => $colorAttribute->name, 'color' => 'black'],
                ['option' => $sizeAttribute->name, 'color' => 'small'],
            ]
        ];

        $variantSmallWhite = [
            'code' => Str::random(16),
            'price' => rand(100, 3000),
            'cost' => rand(100, 3000),
            'variant' => [
                ['option' => $colorAttribute->name, 'color' => 'white'],
                ['option' => $sizeAttribute->name, 'color' => 'small'],
            ]
        ];

        $product->addVariant($variantSmallBlack);
        $product->addVariant($variantSmallWhite);

        return $product;
    }
}
