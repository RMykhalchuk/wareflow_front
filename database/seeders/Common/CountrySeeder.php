<?php

namespace Database\Seeders\Common;

use App\Models\Entities\Address\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::updateOrCreate(
            ['key' => 'ukraine'],
            ['name' => ['uk' => 'Україна', 'en' => 'Ukraine']]
        );

        Country::updateOrCreate(
            ['key' => 'usa'],
            ['name' => ['uk' => 'Сполучені Штати Америки', 'en' => 'United States of America']]
        );

        Country::updateOrCreate(
            ['key' => 'england'],
            ['name' => ['uk' => 'Англія', 'en' => 'England']]
        );

        Country::updateOrCreate(
            ['key' => 'poland'],
            ['name' => ['uk' => 'Польща', 'en' => 'Poland']]
        );

        Country::updateOrCreate(
            ['key' => 'germany'],
            ['name' => ['uk' => 'Німеччина', 'en' => 'Germany']]
        );
    }
}
