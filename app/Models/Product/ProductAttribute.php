<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
            $terms = collect($value)->map(function($term) {
                return ['value' => $term];
            })
            ->values()
            ->toArray();

            return $this->values()->createMany($terms);
        }

        return $this->values()->create(['value' => $value]);
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
