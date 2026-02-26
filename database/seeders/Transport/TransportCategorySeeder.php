<?php

namespace Database\Seeders\Transport;


use App\Models\Dictionaries\TransportCategory;
use Illuminate\Database\Seeder;

class TransportCategorySeeder extends Seeder
{
    public $items = [
        [
            'key' => 'lorry',
            'name' => ['uk' => 'Вантажівка', 'en' => 'Truck'],
        ],
        [
            'key' => 'lorry_with_trailer',
            'name' => ['uk' => 'Вантажівка з причіпом', 'en' => 'Truck with trailer'],
        ],
        [
            'key' => 'truck_tractor',
            'name' => ['uk' => 'Тягач', 'en' => 'Truck tractor'],
        ],
        [
            'key' => 'van',
            'name' => ['uk' => 'Бус', 'en' => 'Van'],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items as $item) {
            TransportCategory::updateOrCreate(
                ['key' => $item['key']],
                ['name' => $item['name']]
            );
        }
    }
}
