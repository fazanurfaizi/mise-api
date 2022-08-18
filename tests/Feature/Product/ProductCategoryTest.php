<?php

namespace Tests\Feature\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * It Will create category then create product
     * Product should have category
     *
     * @return void
     */
    public function itShouldHaveCategory()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id
        ]);

        $this->assertEquals($category->name, $product->category->name, 'Category should be attached to product');
    }

    /**
     * @test
     * It should have child category
     */
    public function itShouldHaveChildCategory()
    {
        $parentCategory = ProductCategory::factory()->create();
        $childCategory = ProductCategory::factory()->create([
            'parent_id' => $parentCategory->id
        ]);

        $this->assertEquals($parentCategory->name, $childCategory->parent->name, 'Parent should equal');
		$this->assertTrue(!$childCategory->isParent(), 'Is Parent should return false');
    }

    /**
     * @test
     * It should list products by category
     */
    public function itShouldListProductsByCategory()
    {
        $category = ProductCategory::factory()->create();
        $products = Product::factory(rand(10, 20))->create([
            'category_id' => $category->id
        ]);

        $this->assertEquals(sizeof($products->toArray()), sizeof($category->products->toArray()), 'It should have the same length');
    }
}
