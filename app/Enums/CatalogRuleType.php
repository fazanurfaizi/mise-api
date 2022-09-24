<?php

namespace App\Enums;

enum CatalogRuleType:int
{
    case PRODUCT_TITLE = 1;
    case PRODUCT_PRICE = 2;
    case COMPARE_AT_PRICE = 3;
    case INVENTORY_STOCK = 4;
    case PRODUCT_BRAND = 5;
    case PRODUCT_CATEGORY = 6;
}
