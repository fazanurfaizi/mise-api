<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Product\ProductAttribute
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\ProductAttributeValue[] $values
 * @property-read int|null $values_count
 * @method static \Database\Factories\Product\ProductAttributeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductAttribute onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductAttribute withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductAttribute withoutTrashed()
 * @mixin \Eloquent
 */
class ProductAttribute extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        // 'product_id',
        'name'
    ];

    /**
     * Fields that can't be assign
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Add Value on the attribute
     *
     * @param string|array $value
     */
    public function addValue($value)
    {
        if(is_array($value)) {
            collect($value)->map(function($term) {
                return ['value' => $term];
            })
            ->values()
            ->each(function($term) {
                $this->values()->firstOrCreate($term);
            });
        }

        $this->values()->firstOrCreate(['value' => $value]);
    }

    /**
     * Remove a term on an attribute
     *
     * @param string $term
     */
    public function removeValue($term)
    {
        return $this->values()->where('value', $term)->firstOrFail()->delete();
    }

    /**
     * Get the product that owns the Attribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_has_attribute',
            'attribute_id',
            'product_id'
        )->using(ProductHasAttribute::class)
        ->withTimestamps()
        ->whereNull('product_has_attribute.deleted_at');
    }

    /**
     * Get all of the valuess for the Attribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, 'attribute_id', 'id');
    }
}
