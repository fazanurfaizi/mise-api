<?php

namespace Database\Seeders;

use App\Models\System\Country;
use App\Traits\Database\DisableForeignKeys;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    use DisableForeignKeys;

    protected array $countries;

    public function __construct() {
        $this->countries = include __DIR__ . '/data/countries.php';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        foreach ($this->countries as $key => $country) {
            Country::query()->create([
                'name' => $country['name']['common'],
                'name_official' => $country['name']['official'],
                'cca2' => $country['cca2'],
                'cca3' => $country['cca3'],
                'flag' => $country['flag'],
                'latitude' => $country['latlng'][0],
                'longitude' => $country['latlng'][1],
                'currencies' => $country['currencies']
            ]);
        }

        $this->enableForeignKeys();
    }
}
