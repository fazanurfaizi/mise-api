<?php

namespace App\Traits\Models;

use NumberFormatter;
use Money\Money;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

trait HasPrice
{
    /**
     * Format price for model's attribute.
     *
     * @param int|string $price
     *
     * @return string
     */
    public function formattedPrice(int|string $price): string
    {
        return mise_money_format($price, mise_currency());
    }
}
