<?php

namespace App\Traits\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasProducts
{
    /**
	 * Assert if the Category has product based name or id
	 *
	 * @param string|int $product
	 * @return bool
	 */
    public function hasProduct($product): bool
    {
        if(is_numeric($product)) {
            return $this->products()->where('id', $product)->exists();
        } else {
            return $this->products()->where('name', $product)->exists();
        }

        return false;
    }

    /**
	 * Assert if the Category has a product based on sku
	 *
	 * @param string $sku
	 * @return bool
	 */
    public function hasProductBySku(string $sku): bool
    {
        return $this->products()->whereHas('skus', function ($query) use ($sku) {
            $query->where('code', $sku);
        })->exists();
    }
}
