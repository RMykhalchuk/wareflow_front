<?php

namespace Database\Seeders\Transport;

use App\Models\Dictionaries\Adr;
use Illuminate\Database\Seeder;

class AdrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Adr::updateOrCreate(
            ['key' => 'adr1'],
            ['name' => ['uk' => 'Adr1', 'en' => 'Adr1']]
        );
        Adr::updateOrCreate(
            ['key' => 'adr2'],
            ['name' => ['uk' => 'Adr2', 'en' => 'Adr2']]
        );
        Adr::updateOrCreate(
            ['key' => 'adr3'],
            ['name' => ['uk' => 'Adr3', 'en' => 'Adr3']]
        );
        Adr::updateOrCreate(
            ['key' => 'adr4'],
            ['name' => ['uk' => 'Adr4', 'en' => 'Adr4']]
        );
        Adr::updateOrCreate(
            ['key' => 'adr5'],
            ['name' => ['uk' => 'Adr5', 'en' => 'Adr5']]
        );
    }
}
