<?php

namespace Database\Seeders\SKU;

use App\Models\Dictionaries\PackageType;
use Illuminate\Database\Seeder;

class PackageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PackageType::updateOrCreate([
            'key' => 'type1',
            'name' => 'Type1'
        ]);

        PackageType::updateOrCreate([
            'key' => 'type2',
            'name' => 'Type2'
        ]);

        PackageType::updateOrCreate([
            'key' => 'type3',
            'name' => 'Type3'
        ]);

        PackageType::updateOrCreate([
            'key' => 'type4',
            'name' => 'Type4'
        ]);

        PackageType::updateOrCreate([
            'key' => 'type5',
            'name' => 'Type5'
        ]);

        PackageType::updateOrCreate([
            'key' => 'type6',
            'name' => 'Type6'
        ]);
    }
}
