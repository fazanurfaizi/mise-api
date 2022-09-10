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
        $product = Product::factory()->create();

        $sizeAttribute = ProductAttribute::factory()->create([
            'name' => 'size'
        ]);
        $sizeTerms = ['small', 'medium', 'large'];
        $colorAttribute = ProductAttribute::factory()->create([
            'name' => 'color'
        ]);
        $colorTerm = ['black', 'white'];

        // Set the terms and attributes
        $product->addAttribute($sizeAttribute->name);
        $product->addAttribute($colorAttribute->name);
        $product->addAttributeTerm($sizeAttribute->name, $sizeTerms);
        $product->addAttributeTerm($colorAttribute->name, $colorTerm);

        $variantSmallBlack = [
			'sku' => 'WOOPROTSHIRT-SMBLK',
			'price' => rand(100,300),
			'cost' => rand(50, 99),
			'variant' => [
				['option' => 'color', 'value' => 'black'],
				['option' => 'size', 'value' => 'small'],
			]
		];
		$variantSmallWhite = [
			'sku' => 'WOOPROTSHIRT-SMWHT',
			'price' => rand(100,300),
			'cost' => rand(50, 99),
			'variant' => [
				['option' => 'color', 'value' => 'white'],
				['option' => 'size', 'value' => 'small'],
			]
		];
		$product->addVariant($variantSmallBlack);
		$product->addVariant($variantSmallWhite);

        return $product;
    }
}
