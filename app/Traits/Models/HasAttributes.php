<?php

namespace App\Traits\Models;

use Exception;
use App\Exceptions\InvalidAttributeException;
use App\Models\Product\ProductAttribute;
use App\Models\Product\Pivot\ProductHasAttribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

trait HasAttributes
{
    /**
     * Create a product attribute
     *
     * @param string $value
     * @throws Exception
     * @return $this
     */
    public function addAttribute(string $value)
    {
        DB::beginTransaction();

        try {
            $attribute = ProductAttribute::where(['name' => $value])->first();
            if($this->hasAttribute($value)) {
                $this->removeAttribute($attribute->id);
                $this->attributes()->attach($attribute);
            } else {
                $this->attributes()->attach($attribute);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw new Exception($e->getMessage(), 422);
        }

        return $this;
    }

    /**
     * Create multiple attribute
     *
     * @param \Illuminate\Database\Eloquent\Collection $attributes
     * @throws Exception
     * @return $this
     */
    public function addAttributes(Collection $attributes)
    {
        DB::beginTransaction();

        try {
            $this->attributes()->attach($attributes->pluck('id')->toArray());

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw new Exception($e->getMessage(), 422);
        }

        return $this;
    }

    /**
	 * It should remove attribute from product
	 *
	 * @param int $id
	 * @return self
	 */
    public function removeAttribute($id)
    {
        DB::beginTransaction();

        try {
            $this->attributes()->detach($id);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw new Exception($e->getMessage(), 422);
        }

        return $this;
    }

     /**
	 * Add Option Value on the attribute
	 *
	 * @param string $option
	 * @param mixed $value
	 * @throws Exception
	 * @return App\Models\Product\ProductAttributeValue
	 */
    public function addAttributeTerm(string $option, $value)
    {
        $attribute = ProductAttribute::where('name', $option)->first();

        if(!$attribute) {
            throw new InvalidAttributeException("Invalid attribute", 422);
        }

        return $attribute->addValue($value);
    }

    /**
	 * It should remove attribute from product
	 *
	 * @param string $key
	 * @return self
	 */
    public function removeAttributeTerm(string $attribute, string $term)
    {
        DB::beginTransaction();

        try {
            $attribute = $this->attributes()->where('name', $attribute)->firstOrFail();
            $attribute->removeValue($term);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw new Exception($e->getMessage(), 422);
        }

        return $this;
    }

    /**
	 * Assert if the Product has attributes
	 *
	 * @return bool
	 */
    public function hasAttributes(): bool
    {
        return !!$this->attributes()->count();
    }

    /**
	 * Assert if the product has this attributes
	 *
	 * @param string|int $key
	 *
	 * @return bool
	 */
    public function hasAttribute($key): bool
    {
        // If the key is a numeric use the id else use the name
        if(is_numeric($key)) {
            return $this->attributes()->where('id', $key)->exists();
        } else {
            return $this->attributes()->where('name', $key)->exists();
        }

        return false;
    }

    /**
     * Get product attributes
     */
    public function loadAttributes()
    {
        return $this->attributes()->get()->load('values');
    }

    /**
     * Get all of the attributes for the HasAttributes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttribute::class,
            'product_has_attribute',
            'product_id',
            'attribute_id'
        )->using(ProductHasAttribute::class)
        ->withTimestamps();
    }

}
