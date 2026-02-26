<?php

namespace Database\Seeders\User;

use App\Models\Dictionaries\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::updateOrCreate(
            ['key' => 'palet'],
            ['name' => ['uk' => 'Палетувальник', 'en' => 'Palletizer']]
        );

        Position::updateOrCreate(
            ['key' => 'complect1'],
            ['name' => ['uk' => 'Комплектувальник Бр 1', 'en' => 'Picker Br 1']]
        );

        Position::updateOrCreate(
            ['key' => 'complect2'],
            ['name' => ['uk' => 'Комплектувальник Бр 2', 'en' => 'Picker Br 2']]
        );

        Position::updateOrCreate(
            ['key' => 'complect3'],
            ['name' => ['uk' => 'Комплектувальник Бр 3', 'en' => 'Picker Br 3']]
        );

        Position::updateOrCreate(
            ['key' => 'complect4'],
            ['name' => ['uk' => 'Комплектувальник Бр 4', 'en' => 'Picker Br 4']]
        );

        Position::updateOrCreate(
            ['key' => 'complect5'],
            ['name' => ['uk' => 'Комплектувальник Бр 5', 'en' => 'Picker Br 5']]
        );

        Position::updateOrCreate(
            ['key' => 'driver'],
            ['name' => ['uk' => 'Водій', 'en' => 'Driver']]
        );

        Position::updateOrCreate(
            ['key' => 'logist'],
            ['name' => ['uk' => 'Логіст', 'en' => 'Logist']]
        );

        Position::updateOrCreate(
            ['key' => 'dispatcher'],
            ['name' => ['uk' => 'Диспечер', 'en' => 'Dispatcher']]
        );

        //        Position::updateOrCreate([
        //            'key' => 'driver_warehouse1',
        //            'name' => 'Водій складу Бр 1'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'driver_warehouse2',
        //            'name' => 'Водій складу Бр 2'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'driver_warehouse3',
        //            'name' => 'Водій складу Бр 3'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'driver_yard_advent',
        //            'name' => 'Водій двору Прихід'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'driver_warehouse_discharge',
        //            'name' => 'Водій двору Розхід'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'driver_warehouse_replenishment',
        //            'name' => 'Водій двору Поповнення'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'driver_yard',
        //            'name' => 'Водій двору'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'commodity_expert',
        //            'name' => 'Товарознавець'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'foreman_controller1',
        //            'name' => 'Контролер-бригадир 1'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'foreman_controller',
        //            'name' => 'Контролер-бригадир'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'foreman_controller2',
        //            'name' => 'Контролер-бригадир 2'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'foreman_controller3',
        //            'name' => 'Контролер-бригадир 3'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'warehouse_operator_retail',
        //            'name' => 'Оператор складу Роздріб'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'warehouse_operator_wholesale',
        //            'name' => 'Оператор складу Гурт'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'storekeeper_retail',
        //            'name' => 'Комірник Роздріб'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'storekeeper_wholesale_advent',
        //            'name' => 'Комірник гурту Прихід'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'storekeeper_wholesale_discharge',
        //            'name' => 'Комірник гурту Розхід'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'dispatcher_warehouse',
        //            'name' => 'Диспетчер складу'
        //        ]);
        //        Position::updateOrCreate([
        //            'key' => 'lead_warehouse',
        //            'name' => 'Керівник складу'
        //        ]);
    }
}
