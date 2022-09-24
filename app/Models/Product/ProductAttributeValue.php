<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Product\ProductAttributeValue
 *
 * @property int $id
 * @property int $attribute_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Product\ProductAttribute $attribute
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\ProductVariant[] $variants
 * @property-read int|null $variants_count
 * @method static \Database\Factories\Product\ProductAttributeValueFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductAttributeValue onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|ProductAttributeValue withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductAttributeValue withoutTrashed()
 * @mixin \Eloquent
 */
class ProductAttributeValue extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'attribute_id',
        'value'
    ];

    /**
     * Fields that can't be assigned
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Get the attribute that owns the AttributeValue
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    /**
     * Get all of the variants for the AttributeValue
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
