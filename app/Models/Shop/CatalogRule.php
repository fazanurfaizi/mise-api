<?php

namespace App\Models\Shop;

use App\Enums\CatalogRuleType;
use App\Enums\CatalogRuleOperator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Shop\CatalogRule
 *
 * @property int $id
 * @property int $catalog_id
 * @property CatalogRuleType $rule '1: product_title', '2: product_price', '3: compare_at_price', '4: inventory_stock', '5: product_brand', '6: product_category'
 * @property CatalogRuleOperator $operator '1: equals_to', '2: not_equals_to', '3: less_than', '4: greater_than', '5: starts_with', '6: ends_with', '7: contains', '8: not_contains'
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Shop\Catalog $catalog
 * @method static \Database\Factories\Shop\CatalogRuleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogRule query()
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogRule whereCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogRule whereOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogRule whereRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogRule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogRule whereValue($value)
 * @mixin \Eloquent
 */
class CatalogRule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rule',
        'operator',
        'value',
        'catalog_id',
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rule' => CatalogRuleType::class,
        'operator' => CatalogRuleOperator::class
    ];

    /**
     * Get the catalog that owns the CatalogRule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'catalog_id', 'id');
    }

    public function getFormattedRule(): string
    {
        return [
            CatalogRuleType::PRODUCT_TITLE => 'Product title',
            CatalogRuleType::PRODUCT_BRAND => 'Product Brand',
            CatalogRuleType::PRODUCT_CATEGORY => 'Product Category',
            CatalogRuleType::PRODUCT_PRICE => 'Product Price',
            CatalogRuleType::COMPARE_AT_PRICE => 'Compare at Price',
            CatalogRuleType::INVENTORY_STOCK => 'Inventory Stock',
        ][$this->rule];
    }

    public function getFormattedOperator(): string
    {
        return [
            CatalogRuleOperator::EQUALS_TO => 'Equals To',
            CatalogRuleOperator::NOT_EQUALS_TO => 'Not Equals To',
            CatalogRuleOperator::LESS_THAN => 'Less Than',
            CatalogRuleOperator::GREATER_THAN => 'Greater Than',
            CatalogRuleOperator::STARTS_WITH => 'Starts With',
            CatalogRuleOperator::ENDS_WITH => 'Ends With',
            CatalogRuleOperator::CONTAINS => 'Contains',
            CatalogRuleOperator::NOT_CONTAINS => 'Not Contains',
        ][$this->operator];
    }

    public function getFormattedValue(): string
    {
        // if($this->rule === CatalogRuleType::PRODUCT_PRICE) {
        //     return money_format(strtoupper($this->value));
        // }

        return $this->value;
    }

}
