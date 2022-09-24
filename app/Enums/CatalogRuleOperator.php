<?php

namespace App\Enums;

enum CatalogRuleOperator:int
{
    case EQUALS_TO = 1;
    case NOT_EQUALS_TO = 2;
    case LESS_THAN = 3;
    case GREATER_THAN = 4;
    case STARTS_WITH = 5;
    case ENDS_WITH = 6;
    case CONTAINS = 7;
    case NOT_CONTAINS = 8;
}
