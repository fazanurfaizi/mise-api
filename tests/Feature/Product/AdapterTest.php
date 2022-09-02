<?php

namespace Tests\Feature\Product;

use App\Adapters\ProductAdapter;
use App\Models\Product\Product;
use App\Models\Product\ProductAttribute;
use App\Models\Product\ProductAttributeValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdapterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itShouldReturnArrayResource()
    {
        $product = Product::factory()->create();

        $productResource = new ProductAdapter($product);

        $this->assertArrayHasKey('slug', $productResource->transform(), 'It should return the response');
        $this->assertArrayHasKey('category', $productResource->transform(), 'It should have keys');
    }

    /**
     * @test
     */
    public function itShouldReturnArrayResourceWithAttributes()
    {
        $product = Product::factory()->create();
        $size = rand(2, 4);
        $attributes = ProductAttribute::factory($size)->make();

        $product->addAttributes($attributes->toArray());

        $product->attributes->each(function(ProductAttribute $attribute) {
            $terms = ProductAttributeValue::factory(3)->make();
            $terms->each(function(ProductAttributeValue $term) use ($attribute) {
                $attribute->addValue($term->value);
            });
        });

        $productResource = new ProductAdapter($product);

        $this->assertArrayHasKey('attributes', $productResource->transform(), 'It should have an attributes');
		$this->assertArrayHasKey('category', $productResource->transform(), 'It should have keys');
    }

    /**
     * @test
     */
    public function itShouldReturnCollectionResource()
    {
        $size = rand(2, 4);
        $products = Product::factory($size)->create();
        $productsCollection = ProductAdapter::collection($products);
        $selected = sizeof($productsCollection) < 1 ? 0 : rand(0, sizeof($productsCollection) - 1);

        $this->assertArrayHasKey('slug', $productsCollection[$selected], 'It should have slug');
		$this->assertEquals($size, sizeof($productsCollection), 'Collection should match the random size');
    }
}
