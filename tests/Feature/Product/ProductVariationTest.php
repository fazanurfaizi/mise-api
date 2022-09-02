<?php

namespace Tests\Feature\Product;

use App\Adapters\ProductAdapter;
use App\Adapters\ProductVariantAdapter;
use App\Exceptions\InvalidVariantException;
use App\Models\Product\Product;
use App\Models\Product\ProductAttribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;

class ProductVariationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function itShouldHaveProductVariant()
    {
        $product = Product::factory()->create();

        $sizeAttribute = ProductAttribute::factory()->make([
            'name' => 'size'
        ]);
        $sizeTerms = ['small', 'medium', 'large'];
        $colorAttribute = ProductAttribute::factory()->make([
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

		$productResource = new ProductAdapter($product);

        $this->assertArrayHasKey('variations', $productResource->transform(), 'It should have a variation');
    }

    /**
     * @test
     */
    public function itShouldFindProductBySku()
    {
        $product = Product::factory()->create();

        $sizeAttribute = ProductAttribute::factory()->make([
            'name' => 'size'
        ]);
        $sizeTerms = ['small', 'medium', 'large'];
        $colorAttribute = ProductAttribute::factory()->make([
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

        $variantResource = new ProductVariantAdapter($product->findBySku('WOOPROTSHIRT-SMWHT'));

        $this->assertArrayHasKey('sku', $variantResource->transform(), 'It should have an sku');
    }

    public function itShouldListTheVariations()
    {
        $product = Product::factory()->create();

        $sizeAttribute = ProductAttribute::factory()->make([
            'name' => 'size'
        ]);
        $sizeTerms = ['small', 'medium', 'large'];
        $colorAttribute = ProductAttribute::factory()->make([
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

        $variantResource = new ProductVariantAdapter($product->findBySku('WOOPROTSHIRT-SMWHT'));
        $this->assertArrayHasKey('sku', $variantResource->transform(), 'It should have an sku');
		$this->assertArrayHasKey('parent_product_id', $variantResource->transform(), 'It should have a parent_product_id');
    }

    public function itShouldListCollectionOfVariations()
    {
        $product = Product::factory()->create();

        $sizeAttribute = ProductAttribute::factory()->make([
            'name' => 'size'
        ]);
        $sizeTerms = ['small', 'medium', 'large'];
        $colorAttribute = ProductAttribute::factory()->make([
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

        $variantResource = new ProductVariantAdapter($product->findBySku('WOOPROTSHIRT-SMWHT'));
        $this->assertArrayHasKey('parent_product_id', head($variantResource), 'It should have a parent_product_id');
    }
}
