<?php

namespace App\Models\Product;

use App\Enums\ProductCondition;
use App\Models\Product\Pivot\ProductHasCategory;
use App\Models\Product\Pivot\ProductHasUnit;
use App\Traits\Models\Sluggable;
use App\Traits\Models\HasAttributes;
use App\Traits\Models\HasVariants;
use App\Traits\Models\HasInventory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Product\Product
 *
 * @property int $id
 * @property string $name
 * @property int $brand_id
 * @property string $slug
 * @property string|null $description
 * @property ProductCondition|null $condition
 * @property int $min_purchase
 * @property int|null $featured
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\ProductAttribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \App\Models\Product\Brand $brand
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\ProductCategory[] $categories
 * @property-read int|null $categories_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\ProductSku[] $skus
 * @property-read int|null $skus_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\ProductUnit[] $units
 * @property-read int|null $units_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\ProductVariant[] $variants
 * @property-read int|null $variants_count
 * @method static \Database\Factories\Product\ProductFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMinPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku(string $sku)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory\InventoryStock[] $warehouses
 * @property-read int|null $warehouses_count
 */
class Product extends Model implements HasMedia
{
    use HasFactory,
        SoftDeletes,
        Sluggable,
        HasAttributes,
        HasVariants,
        HasInventory,
        InteractsWithMedia;

    /**
	 * Fields that are mass assignable
	 *
	 * @var array
	 */
    protected $fillable = [
        'name',
        'brand_id',
        'slug',
        'description',
        'condition',
        'min_purchase',
        'featured'
    ];

    /**
	 * Guarded Fields
	 *
	 * @var array
	 */
	protected $guarded = [
		'id',
        'created_at',
        'updated_at'
	];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'condition' => ProductCondition::class,
    ];

    /**
	 * Sluggable field of the model
	 *
	 * @var string
	 */
	protected $sluggable = 'name';

    /**
     * Get the brand that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the categories that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'product_has_category',
            'product_id',
            'category_id'
        )->using(ProductHasCategory::class)
        ->withTimestamps();
    }

    /**
     * The units that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductUnit::class,
            'product_has_unit',
            'product_id',
            'unit_id'
        )->using(ProductHasUnit::class)
        ->withPivot('value')
        ->withTimestamps();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products')
            ->useFallbackUrl(url('/images/product_placeholder.jpg'));
    }

    /**
     * Generate image thumbnail
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

}
