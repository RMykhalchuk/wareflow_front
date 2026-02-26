<?php

namespace Database\Seeders\Warehouse;

use App\Models\Dictionaries\WarehouseType;
use App\Models\Entities\WarehouseComponents\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseTypeCleanupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        WarehouseType::updateOrCreate(
            ['id' => 1],
            [
                'key'  => 'own_w',
                'name' => ['uk' => 'Власний склад', 'en' => 'Own warehouse'],
            ]
        );

        WarehouseType::updateOrCreate(
            ['id' => 2],
            [
                'key'  => 'rented_w',
                'name' => ['uk' => 'Найманий склад', 'en' => 'Rented warehouse'],
            ]
        );

        WarehouseType::whereNotIn('id', [1, 2])->delete();


        $this->command->info('The warehouse directory has been updated: only two types remain.');
    }
}
