<?php

namespace Database\Seeders\Transport;

use App\Models\Dictionaries\TransportType;
use Illuminate\Database\Seeder;

class TransportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransportType::updateOrCreate(
            ['key' => 'type1'],
            ['name' => ['uk' => 'Тип 1', 'en' => 'Type 1']]
        );

        TransportType::updateOrCreate(
            ['key' => 'type2'],
            ['name' => ['uk' => 'Тип 2', 'en' => 'Type 2']]
        );

        TransportType::updateOrCreate(
            ['key' => 'type3'],
            ['name' => ['uk' => 'Тип 3', 'en' => 'Type 3']]
        );

        TransportType::updateOrCreate(
            ['key' => 'type4'],
            ['name' => ['uk' => 'Тип 4', 'en' => 'Type 4']]
        );

        TransportType::updateOrCreate(
            ['key' => 'type5'],
            ['name' => ['uk' => 'Тип 5', 'en' => 'Type 5']]
        );
    }
}
