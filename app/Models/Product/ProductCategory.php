<?php

namespace App\Models\Product;

use App\Models\Product\Pivot\ProductHasCategory;
use App\Traits\Models\HasProducts;
use App\Traits\Models\SelfReference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Product\ProductCategory
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $sku
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $children
 * @property-read int|null $children_count
 * @property-read ProductCategory|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\Product[] $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\Product\ProductCategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory parentOnly()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductCategory withoutTrashed()
 * @mixin \Eloquent
 */
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
        ->withTimestamps();
    }
}
