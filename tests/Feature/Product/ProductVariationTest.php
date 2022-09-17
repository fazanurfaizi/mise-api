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
use Tests\Traits\CreateProducts;

class ProductVariationTest extends TestCase
{
    use RefreshDatabase, CreateProducts;
    /**
     * @test
     */
    public function itShouldHaveProductVariant()
    {
        $product = $this->createTestProduct();

		$productResource = new ProductAdapter($product);

        $this->assertArrayHasKey('variants', $productResource->transform(), 'It should have a variation');
    }

    /**
     * @test
     */
    public function itShouldFindProductBySku()
    {
        $product = $this->createTestProduct();

        $variantResource = new ProductVariantAdapter($product->findBySku('WOOPROTSHIRT-SMWHT'));

        $this->assertArrayHasKey('sku', $variantResource->transform(), 'It should have an sku');
    }

    /**
     * @test
     */
    public function itShouldListTheVariations()
    {
        $product = $this->createTestProduct();

        $variantResource = new ProductVariantAdapter($product->findBySku('WOOPROTSHIRT-SMWHT'));
        $this->assertArrayHasKey('sku', $variantResource->transform(), 'It should have an sku');
		$this->assertArrayHasKey('parent_product_id', $variantResource->transform(), 'It should have a parent_product_id');
    }

    /**
     * @test
     */
    public function itShouldListCollectionOfVariations()
    {
        $product = $this->createTestProduct();

        $variantResource = ProductVariantAdapter::collection($product->getVariations());
        $this->assertArrayHasKey('parent_product_id', head($variantResource), 'It should have a parent_product_id');
    }
}
