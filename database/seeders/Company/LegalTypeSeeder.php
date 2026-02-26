<?php

namespace Database\Seeders\Company;

use App\Models\Dictionaries\LegalType;
use Illuminate\Database\Seeder;

class LegalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LegalType::updateOrCreate([
            'key' => 'type_1',
            'name' => 'Тип 1'
        ]);
        LegalType::updateOrCreate([
            'key' => 'type_2',
            'name' => 'Тип 2'
        ]);
        LegalType::updateOrCreate([
            'key' => 'type_3',
            'name' => 'Тип 3'
        ]);
    }
}
