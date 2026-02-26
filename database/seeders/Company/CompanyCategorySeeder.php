<?php

namespace Database\Seeders\Company;

use App\Models\Dictionaries\CompanyCategory;
use Illuminate\Database\Seeder;

class CompanyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyCategory::updateOrCreate(
            ['key' => 'producer'],
            ['name' => ['uk' => 'Виробник', 'en' => 'Producer']]
        );

        CompanyCategory::updateOrCreate(
            ['key' => 'provider'],
            ['name' => ['uk' => 'Постачальник', 'en' => 'Supplier']]
        );

        CompanyCategory::updateOrCreate(
            ['key' => 'distributor'],
            ['name' => ['uk' => 'Дистрибютор', 'en' => 'Distributor']]
        );

        CompanyCategory::updateOrCreate(
            ['key' => 'supermarket'],
            ['name' => ['uk' => 'Супермаркет', 'en' => 'Supermarket']]
        );

        CompanyCategory::updateOrCreate(
            ['key' => 'carrier'],
            ['name' => ['uk' => 'Перевізник', 'en' => 'Carrier']]
        );

        CompanyCategory::updateOrCreate(
            ['key' => '3pl'],
            ['name' => ['uk' => '3PL - оператор', 'en' => '3PL Operator']]
        );
    }
}
