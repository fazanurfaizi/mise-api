<?php

namespace App\Actions\Products\Product;

use Exception;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProduct
{
    use AsAction;

    /**
     * Update product action
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product\Product $product
     * @return \App\Models\Product\Product $product
     */
    public function handle(Request $request, Product $product): Product
    {
        try {
            DB::beginTransaction();

            $product->update([
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

            DB::commit();

            return $product;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
