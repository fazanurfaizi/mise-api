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

/**
 * App\Models\Shop\Catalog
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property CatalogType $type
 * @property CatalogSort $sort '1: best_selling', '2: alpha_asc', '3: alpha_desc', '4: price_desc', '5: price_asc', '6: created_desc', '7: created_asc', '8: manual'
 * @property string $match_conditions
 * @property string $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductSku[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shop\CatalogRule[] $rules
 * @property-read int|null $rules_count
 * @method static Builder|Catalog automatic()
 * @method static \Database\Factories\Shop\CatalogFactory factory(...$parameters)
 * @method static Builder|Catalog manual()
 * @method static Builder|Catalog newModelQuery()
 * @method static Builder|Catalog newQuery()
 * @method static \Illuminate\Database\Query\Builder|Catalog onlyTrashed()
 * @method static Builder|Catalog query()
 * @method static Builder|Catalog whereCreatedAt($value)
 * @method static Builder|Catalog whereDeletedAt($value)
 * @method static Builder|Catalog whereDescription($value)
 * @method static Builder|Catalog whereId($value)
 * @method static Builder|Catalog whereMatchConditions($value)
 * @method static Builder|Catalog whereName($value)
 * @method static Builder|Catalog wherePublishedAt($value)
 * @method static Builder|Catalog whereSlug($value)
 * @method static Builder|Catalog whereSort($value)
 * @method static Builder|Catalog whereType($value)
 * @method static Builder|Catalog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Catalog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Catalog withoutTrashed()
 * @mixin \Eloquent
 */
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
