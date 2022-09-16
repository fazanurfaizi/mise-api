<?php

namespace App\Actions\ProductSku;

use App\Models\Product\Product;
use App\Models\Product\ProductAttribute;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachProductSku
{
    use AsAction;

    /**
     * Attach Product SKU Action
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product\Product $product
     * @return \App\Models\Product\Product
     */
    public function handle(Request $request, Product $product): Product
    {
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
            ], $sku['id'] ?? null);
        }

        return $product;
    }
}
