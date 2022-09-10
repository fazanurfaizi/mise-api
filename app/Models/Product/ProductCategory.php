<?php

namespace App\Models\Product;

use App\Traits\Models\HasProducts;
use App\Traits\Models\SelfReference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory,
        SoftDeletes,
        SelfReference,
        HasProducts;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'sku',
        'image'
    ];

    /**
     * Get all of the products for the ProductCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_has_category',
            'category_id',
            'product_id'
        )->using(ProductHasCategory::class)
        ->withTimestamps()
        ->whereNull('product_has_category.deleted_at');
    }
}
