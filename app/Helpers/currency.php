<?php

use App\Models\System\Currency;
use Illuminate\Support\Facades\Cache;
use Money\Currencies\ISOCurrencies;
use Money\Currency as MoneyCurrency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

if(!function_exists('mise_currency')) {
    /**
     * Return Mise currency used.
     *
     * @return string
     */
    function mise_currency(): string
    {
        $settingCurrency = mise_setting('shop_currency_id');

        if($settingCurrency) {
            $currency = Cache::remember('mise-currency', now()->addHour(), fn() => Currency::query()->find($settingCurrency));

            return $currency ? $currency->code : config('app.mise_currency');
        }

        return config('app.mise_currency');
    }
}

if(!function_exists('mise_money_format')) {
    /**
     * Return Mise monet format.
     *
     * @param int|string $amount
     * @param string|null $currency
     *
     * @return string
     */
    function mise_money_format(int|string $amount, ?string $currency = null): string
    {
        $money = new Money($amount, new MoneyCurrency($currency ?? mise_currency()));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}
