<?php

namespace App\Actions\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductAttribute;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateProduct
{
    use AsAction;

    /**
     * Store product action.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Models\Product\Product
     */
    public function handle(Request $request)
    {
        $product = Product::create([
            'name' => $request->post('name'),
            'brand_id' => $request->post('brand_id'),
            'description' => $request->post('description'),
            'condition' => $request->post('condition'),
            'min_purchase' => $request->post('min_purchase'),
            'featured' => $request->post('featured'),
        ]);

        $product->categories()->sync($request->post('categories'));

        if($request->hasFile('images')) {
            collect($request->images)->each(
                fn ($file) => $product
                    ->addMedia($file)
                    ->withResponsiveImages()
                    ->toMediaCollection('products')
            );
        }

        if($request->has('units')) {
            foreach ($request->post('units') as $unit) {
                $product->units()->attach($unit['unit'], [
                    'value' => $unit['value']
                ]);
            }
        }

        if($request->has('skus')) {
            foreach ($request->skus as $sku) {
                collect($sku['variant'])->each(function($variant) use ($product) {
                    ProductAttribute::firstOrCreate([
                        'name' => $variant['option']
                    ]);

                    $product->addAttribute($variant['option']);
                    $product->addAttributeTerm($variant['option'], $variant['value']);
                });

                $product->addVariant([
                    'sku' => $sku['code'],
                    'price' => $sku['price'],
                    'cost' => $sku['cost'],
                    'variant' => $sku['variant']
                ]);
            }
        }

        return $product;
    }
}
