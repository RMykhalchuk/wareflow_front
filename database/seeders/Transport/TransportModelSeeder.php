<?php

namespace Database\Seeders\Transport;

use App\Models\Dictionaries\TransportModel;
use Illuminate\Database\Seeder;

class TransportModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = storage_path('import/transport_models.csv');
        $file = fopen($filePath, 'r');

        $header = fgetcsv($file);

        $data = [];
        while ($row = fgetcsv($file)) {
            $data[] = array_combine($header, $row);
        }

        fclose($file);

        foreach ($data as $row) {
            TransportModel::updateOrCreate(
                ['key' => $row['key']],
                [
                    'name' => $row['name'],
                    'brand_id' => $row['brand_id']
                ]
            );
        }
    }
}
