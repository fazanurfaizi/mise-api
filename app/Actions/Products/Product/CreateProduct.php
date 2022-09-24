<?php

namespace App\Actions\Products\Product;

use Exception;
use App\Models\Product\Product;
use App\Actions\Products\ProductSku\AttachProductSku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        try {
            DB::beginTransaction();

            $product = Product::query()->create([
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
                AttachProductSku::run($request, $product);
            }

            DB::commit();

            return $product;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
