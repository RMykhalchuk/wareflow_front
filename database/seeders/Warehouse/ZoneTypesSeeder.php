<?php

namespace Database\Seeders\Warehouse;

use App\Models\Entities\WarehouseComponents\Zone\ZoneType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zoneTypes = [
            1 => ['key' => 'storage',   'name' => ['uk' => 'Зона зберігання', 'en' => 'Storage Area']],
            2 => ['key' => 'operation', 'name' => ['uk' => 'Операційна зона', 'en' => 'Operations Area']],
        ];

        foreach ($zoneTypes as $id => $data) {
            ZoneType::updateOrCreate(
                ['id' => $id],
                ['key' => $data['key'], 'name' => $data['name']]
            );
        }
    }
}
