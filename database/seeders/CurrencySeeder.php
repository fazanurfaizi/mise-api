<?php

namespace Database\Seeders;

use App\Models\System\Currency;
use App\Traits\Database\DisableForeignKeys;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    use DisableForeignKeys;

    protected array $currencies;

    public function __construct() {
        $this->currencies = include __DIR__ . '/data/currencies.php';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        foreach ($this->currencies as $key => $currency) {
            $data = array_merge($currency, ['code' => $key]);
            Currency::query()->create($data);
        }

        $this->enableForeignKeys();
    }
}
