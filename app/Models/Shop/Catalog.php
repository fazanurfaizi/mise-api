<?php

namespace App\Models\Shop;

use App\Enums\CatalogSort;
use App\Enums\CatalogType;
use App\Models\Product\ProductSku;
use App\Traits\Models\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Catalog extends Model implements HasMedia
{
    use HasFactory;
    use Sluggable;
    use InteractsWithMedia;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'published_at',
        'type',
        'sort',
        'match_conditions'
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => CatalogType::class,
        'sort' => CatalogSort::class,
    ];

    /**
	 * Sluggable field of the model
	 *
	 * @var string
	 */
	protected $sluggable = 'name';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('catalogs')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpg', 'image/jpeg', 'image/png']);
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

    /**
     * Get all of the products for the Catalog
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function products(): MorphToMany
    {
        return $this->morphToMany(ProductSku::class, 'productable', 'product_has_catalogs');
    }

    /**
     * Get all of the rules for the Catalog
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rules(): HasMany
    {
        return $this->hasMany(CatalogRule::class, 'catalog_id', 'id');
    }

    public function scopeManual(Builder $query): Builder
    {
        return $query->where('type', 'manual');
    }

    public function scopeAutomatic(Builder $query): Builder
    {
        return $query->where('type', 'auto');
    }

    public function isAutomatic(): bool
    {
        return $this->type === 'auto';
    }

    public function isManual(): bool
    {
        return $this->type === 'manual';
    }

    /**
     * Return the correct formatted word of the first collection rule.
     */
    public function firstRule(): string
    {
        $condition = $this->rules()->first();

        return $condition->getFormattedRule() . ' ' . $condition->getFormattedOperator() . ' ' . $condition->getFormattedValue();
    }
}
