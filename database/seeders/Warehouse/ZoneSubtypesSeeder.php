<?php

namespace Database\Seeders\Warehouse;

use App\Models\Entities\WarehouseComponents\Zone\ZoneSubtype;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneSubtypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zoneSubtypes = [
            1  => ['key' => 'pallet_storage',   'name' => ['uk' => 'Палетне зберігання',  'en' => 'Pallet storage']],
            2  => ['key' => 'box_storage',       'name' => ['uk' => 'Коробкове зберігання','en' => 'Box storage']],
            3  => ['key' => 'covered_warehouse', 'name' => ['uk' => 'Критий склад',         'en' => 'Covered warehouse']],
            4  => ['key' => 'outdoor_storage',   'name' => ['uk' => 'Вуличне зберігання',   'en' => 'Outdoor storage']],
            5  => ['key' => 'receiving',         'name' => ['uk' => 'Приймання',            'en' => 'Receiving']],
            6  => ['key' => 'picking',           'name' => ['uk' => 'Комплектації',         'en' => 'Picking']],
            7  => ['key' => 'quality_control',   'name' => ['uk' => 'Контролю',             'en' => 'Quality control']],
            8  => ['key' => 'shipping',          'name' => ['uk' => 'Відвантаження',        'en' => 'Shipping']],
            9  => ['key' => 'cooling',           'name' => ['uk' => 'Охолодження',          'en' => 'Cooling']],
            10 => ['key' => 'repalletizing',     'name' => ['uk' => 'Перепалетування',      'en' => 'Repalletizing']],
        ];

        foreach ($zoneSubtypes as $id => $data) {
            ZoneSubtype::updateOrCreate(
                ['id' => $id],
                ['key' => $data['key'], 'name' => $data['name']]
            );
        }
    }
}
