<?php

namespace App\Traits\Models;

use Exception;
use App\Models\Product\ProductAttribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

trait HasAttributes
{
    /**
     * Create a product attribute
     *
     * @param string $attribute
     * @throws Exception
     * @return $this
     */
    public function addAttribute(string $attribute)
    {
        DB::beginTransaction();

        try {
            $this->attributes()->create(['name' => $attribute]);

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
     * @param array $attribute
     * @throws Exception
     * @return $this
     */
    public function addAttributes(array $attributes)
    {
        DB::beginTransaction();

        try {
            $this->attributes()->createMany($attributes);

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
	 * @param string $key
	 * @return self
	 */
    public function removeAttribute($key)
    {
        DB::beginTransaction();

        try {
            $attribute = $this->attributes()->where('name', $key)->firstOrFail();
            $attribute->delete();

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
	 * Add Option Value on the attribute
	 *
	 * @param string $option
	 * @param mixed $value
	 * @throws Exception
	 * @return App\Models\Product\ProductAttributeValue
	 */
    public function addAttributeTerm(string $option, $value)
    {
        $attribute = $this->attributes()->where('name', $option)->first();

        if(!$attribute) {
            throw new Exception("Invalid attriibute", 422);
        }

        return $attribute->addValue($value);
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

}
