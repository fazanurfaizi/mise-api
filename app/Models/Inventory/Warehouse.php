<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Inventory\Warehouse
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $email
 * @property string $address
 * @property string $city
 * @property string $zipcode
 * @property string|null $phone_number
 * @property int $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory\InventoryStock[] $items
 * @property-read int|null $items_count
 * @method static \Database\Factories\Inventory\WarehouseFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Query\Builder|Warehouse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|Warehouse withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Warehouse withoutTrashed()
 * @mixin \Eloquent
 */
class Warehouse extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'email',
        'address',
        'city',
        'zipcode',
        'phone_number',
        'is_default'
    ];

    /**
     * Guarded fields
     *
     * @var array
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function($warehouse) {
            if($warehouse->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });

        static::updating(function($warehouse) {
            if($warehouse->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });
    }

    /**
     * Get all of the items for the Warehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(InventoryStock::class);
    }
}
