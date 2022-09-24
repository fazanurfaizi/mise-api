<?php

namespace App\Models\Product;

use App\Traits\Models\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Product\Brand
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $website
 * @property string|null $description
 * @property bool $is_enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\Product[] $products
 * @property-read int|null $products_count
 * @method static Builder|Brand enabled()
 * @method static \Database\Factories\Product\BrandFactory factory(...$parameters)
 * @method static Builder|Brand newModelQuery()
 * @method static Builder|Brand newQuery()
 * @method static \Illuminate\Database\Query\Builder|Brand onlyTrashed()
 * @method static Builder|Brand query()
 * @method static Builder|Brand whereCreatedAt($value)
 * @method static Builder|Brand whereDeletedAt($value)
 * @method static Builder|Brand whereDescription($value)
 * @method static Builder|Brand whereId($value)
 * @method static Builder|Brand whereIsEnabled($value)
 * @method static Builder|Brand whereName($value)
 * @method static Builder|Brand whereSlug($value)
 * @method static Builder|Brand whereUpdatedAt($value)
 * @method static Builder|Brand whereWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|Brand withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Brand withoutTrashed()
 * @mixin \Eloquent
 */
class Brand extends Model
{
    use HasFactory,
        SoftDeletes,
        Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'website',
        'description',
        'is_enabled'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    /**
	 * Sluggable field of the model
	 *
	 * @var string
	 */
	protected $sluggable = 'name';

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Get all of the products for the Brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
