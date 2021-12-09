<?php

namespace App\Models\Product;

use App\Traits\Models\Sluggable;
use App\Traits\Models\HasAttributes;
use App\Traits\Models\HasVariants;
use App\Traits\Models\HasInventory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'product_category_id',
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
     * Get the category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }

}
