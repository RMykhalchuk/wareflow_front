<?php

namespace Database\Seeders\Warehouse;

use App\Models\Dictionaries\CellStatus;
use Illuminate\Database\Seeder;

class CellSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CellStatus::updateOrCreate(
            [
                'key' => 'k1e',
                'name' => 'Status 31'
            ]);
    }
}
