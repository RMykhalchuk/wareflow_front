<?php

namespace Database\Seeders\Transport;

use App\Models\Dictionaries\TransportBrand;
use Illuminate\Database\Seeder;

class TransportBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = storage_path('import/transport_brands.csv');
        $file = fopen($filePath, 'r');

        $header = fgetcsv($file);

        $data = [];
        while ($row = fgetcsv($file)) {
            $data[] = array_combine($header, $row);
        }

        fclose($file);

        foreach ($data as $row) {
            TransportBrand::updateOrCreate(
                ['key' => $row['key']],
                ['name' => $row['name']]
            );
        }
    }
}
