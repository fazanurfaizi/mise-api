<?php

namespace App\Models\Product;

use App\Traits\Models\Sluggable;
use App\Traits\Models\HasAttributes;
use App\Traits\Models\HasVariants;
use App\Traits\Models\HasInventory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory,
        SoftDeletes,
        Sluggable,
        HasAttributes,
        HasVariants,
        HasInventory;

    /**
	 * Fields that are mass assignable
	 *
	 * @var array
	 */
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    /**
	 * Guarded Fields
	 *
	 * @var array
	 */
	protected $guarded = [
		'id', 'created_at', 'updated_at'
	];

    /**
	 * Sluggable field of the model
	 *
	 * @var string
	 */
	protected $sluggable = 'name';

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

}
