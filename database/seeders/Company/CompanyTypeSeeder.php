<?php

namespace Database\Seeders\Company;

use App\Models\Dictionaries\CompanyType;
use Illuminate\Database\Seeder;

class CompanyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyType::updateOrCreate(
            ['key' => 'physical'],
            [
                'short_name' => 'Фіз. особа',
                'name' => ['uk' => 'Фізична особа', 'en' => 'Individual']
            ]
        );

        CompanyType::updateOrCreate(
            ['key' => 'legal'],
            [
                'short_name' => 'Юр. особа',
                'name' => ['uk' => 'Юридична особа', 'en' => 'Legal entity']
            ]
        );
    }
}
