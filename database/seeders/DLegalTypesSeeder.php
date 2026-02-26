<?php

namespace Database\Seeders;

use App\Models\Dictionaries\LegalType;
use Illuminate\Database\Seeder;

class DLegalTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LegalType::updateOrCreate(
            ['key' => 'llc'],
            ['name' => ['uk' => 'ТОВ', 'en' => 'LLC']]
        );

        LegalType::updateOrCreate(
            ['key' => 'public_jsc'],
            ['name' => ['uk' => 'Публічне АТ', 'en' => 'PJSC']]
        );

        LegalType::updateOrCreate(
            ['key' => 'private_jsc'],
            ['name' => ['uk' => 'Приватне АТ', 'en' => 'PrJSC']]
        );

        LegalType::updateOrCreate(
            ['key' => 'private_enterprise'],
            ['name' => ['uk' => 'Приватне підприємство', 'en' => 'PE']]
        );

        LegalType::updateOrCreate(
            ['key' => 'other'],
            ['name' => ['uk' => 'Інше', 'en' => 'Other']]
        );
    }
}
