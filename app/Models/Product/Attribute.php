<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all of the valuess for the Attribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function valuess(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }
}
