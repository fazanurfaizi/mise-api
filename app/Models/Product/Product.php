<?php

namespace App\Models\Product;

use App\Enums\ProductCondition;
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
        ->withTimestamps()
        ->whereNull('product_has_category.deleted_at');
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
        ->withTimestamps()
        ->whereNull('product_has_unit.deleted_at');
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
