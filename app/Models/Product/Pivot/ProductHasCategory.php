<?php

namespace App\Models\Product\Pivot;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Product\ProductHasCategory
 *
 * @property int $id
 * @property int $product_id
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product\ProductCategory $category
 * @property-read \App\Models\Product\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasCategory whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductHasCategory extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $table = "product_has_category";

    /**
     * Get the product that owns the ProductHasCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the category that owns the ProductHasCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }
}
