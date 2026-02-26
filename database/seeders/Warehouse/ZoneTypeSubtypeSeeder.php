<?php

namespace Database\Seeders\Warehouse;

use App\Models\Entities\WarehouseComponents\Zone\ZoneSubtype;
use App\Models\Entities\WarehouseComponents\Zone\ZoneType;
use Illuminate\Database\Seeder;

class ZoneTypeSubtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storage   = ZoneType::where('id', 1)->firstOrFail();
        $operation = ZoneType::where('id', 2)->firstOrFail();

        $storageSubtypes = [1, 2, 3, 4];
        $operationSubtypes = [5, 6, 7, 8, 9 ,10];

        $storage->subtypes()->sync($storageSubtypes);
        $operation->subtypes()->sync($operationSubtypes);
    }
}
