<?php

namespace Database\Seeders\Transport;

use App\Models\Dictionaries\DownloadZone;
use Illuminate\Database\Seeder;

class DownloadZonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DownloadZone::updateOrCreate(
            ['key' => 'A'],
            ['name' => ['uk' => 'A', 'en' => 'A']]
        );

        DownloadZone::updateOrCreate(
            ['key' => 'B'],
            ['name' => ['uk' => 'B', 'en' => 'B']]
        );

        DownloadZone::updateOrCreate(
            ['key' => 'C'],
            ['name' => ['uk' => 'C', 'en' => 'C']]
        );

        DownloadZone::updateOrCreate(
            ['key' => 'D'],
            ['name' => ['uk' => 'D', 'en' => 'D']]
        );

        DownloadZone::updateOrCreate(
            ['key' => 'E'],
            ['name' => ['uk' => 'E', 'en' => 'E']]
        );

    }
}
