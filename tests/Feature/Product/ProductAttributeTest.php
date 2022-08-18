<?php

namespace Tests\Feature\Product;

use App\Exceptions\InvalidAttributeException;
use App\Models\Product\Product;
use App\Models\Product\ProductAttribute;
use App\Models\Product\ProductAttributeValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductAttributeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itShouldAddAttributeToProduct()
    {
        $product = Product::factory()->create();
        $attribute = ProductAttribute::factory()->create();

        $product->addAttribute($attribute->name);

        $this->assertTrue($product->hasAttributes());
        $this->assertTrue($product->hasAttribute($attribute->name));
    }

    /**
     * @test
     */
    public function itShouldAddAttributeAndValueToProduct()
    {
        $product = Product::factory()->create();
        $attribute = ProductAttribute::factory()->create([
            'product_id' => $product->id
        ]);
        $option = ProductAttributeValue::factory()->create();

        $product->addAttributeTerm($attribute->name, $option->value);

        $this->assertTrue($attribute->values()->count() > 0);
    }

    /**
     * @test
     */
    public function itShouldGetProductAttributeAndValues()
    {
        $product = Product::factory()->create();
        $attribute = ProductAttribute::factory()->create([
            'product_id' => $product->id
        ]);
        $size = rand(2, 5);

        $options = ProductAttributeValue::factory($size)->create();

        $options->each(function($option) use ($product, $attribute) {
            $product->addAttributeTerm($attribute->name, $option->value);
        });

        $this->assertTrue(sizeof($product->loadAttributes()->first()->toArray()['values']) >= $size, 'It should attach all the options');
    }

    /**
     * @test
     */
    public function itShouldCreateMultipleAttributes()
    {
        $product = Product::factory()->create();
        $size = rand(2, 4);
        $attributes = ProductAttribute::factory($size)->create();

        $attributes->each(function($attribute) use ($product) {
            $product->addAttribute($attribute['name']);
        });

        $this->assertEquals($size, sizeof($product->loadAttributes()->toArray()), 'Attributes should be equal to product attribute');
    }

    /**
     * @test
     */
    public function itShouldCreateMultipleAttributesUsingArray()
    {
        $product = Product::factory()->create();
        $size = rand(2, 4);
        $attributes = ProductAttribute::factory($size)->create();

        $product->addAttributes($attributes->toArray());

        $this->assertEquals($size, sizeof($product->loadAttributes()->toArray()), 'Attributes should be equal to product attribute');
    }

    /** @test */
	public function itShouldThrowInvalidAttributeException()
	{
		$this->expectException(InvalidAttributeException::class);

		$product = Product::factory()->create();

		$product->addAttributeTerm('test', 'test');
	}

	/** @test */
	public function itShouldRemoveAttributeFromProduct()
	{
		$product = Product::factory()->create();
		$size = rand(2,4);
		$attributes = ProductAttribute::factory($size)->create();

		$product->addAttributes($attributes->toArray());

		$selected = sizeof($attributes) < 1 ? 0 : rand(0, sizeof($attributes) - 1);

		$product->removeAttribute($attributes[$selected]['name']);

		$this->assertEquals($size - 1, sizeof($product->loadAttributes()->toArray()), 'Attributes should be equal to product attribute');
	}

	/** @test */
	public function itShouldRemoveAttributeTermFromProduct()
	{
		$product = Product::factory()->create();
		$attribute = ProductAttribute::factory()->create([
			'product_id' => $product->id
		]);
		$size = rand(2,5);

		$options = ProductAttributeValue::factory($size)->create();

		// Add the terms on the product
		$options->each(function ($option) use ($product, $attribute) {
			$product->addAttributeTerm($attribute->name, $option->value);
		});

		$selected = sizeof($options) < 1 ? 0 : rand(0, sizeof($options) - 1);

		$product->removeAttributeTerm($attribute->name, $options[$selected]['value']);


		$this->assertEquals(sizeof($product->loadAttributes()->first()->toArray()['values']), $size - 1, 'It should attach all the options');
	}
}
