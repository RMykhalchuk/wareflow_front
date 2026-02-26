<?php

namespace Database\Seeders\Transport;


use App\Models\Dictionaries\TransportDownload;
use Illuminate\Database\Seeder;

class TransportDownloadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransportDownload::updateOrCreate(
            ['key' => 'method1'],
            ['name' => ['uk' => 'Метод 1', 'en' => 'Method 1']]
        );

        TransportDownload::updateOrCreate(
            ['key' => 'method2'],
            ['name' => ['uk' => 'Метод 2', 'en' => 'Method 2']]
        );

        TransportDownload::updateOrCreate(
            ['key' => 'method3'],
            ['name' => ['uk' => 'Метод 3', 'en' => 'Method 3']]
        );

        TransportDownload::updateOrCreate(
            ['key' => 'method4'],
            ['name' => ['uk' => 'Метод 4', 'en' => 'Method 4']]
        );
    }
}
