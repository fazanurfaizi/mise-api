<?php

namespace App\Models\Shop;

use App\Enums\CatalogRuleType;
use App\Enums\CatalogRuleOperator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
