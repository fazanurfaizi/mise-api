<?php

namespace App\Enums;

enum CatalogSort:int
{
    case BEST_SELLING = 1;
    case ALPHA_ASC = 2;
    case ALPHA_DESC = 3;
    case PRICE_DESC = 4;
    case PRICE_ASC = 5;
    case CREATED_DESC = 6;
    case CREATED_ASC = 7;
    case MANUAL = 8;
}
