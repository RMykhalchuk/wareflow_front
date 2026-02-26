<?php

namespace Database\Seeders\Service;

use App\Models\Dictionaries\ServiceCategories;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public $items = [
        [
            'key' => 'pryiom_tovaru',
            'name' => ['uk' => 'Прийом товару', 'en' => 'Goods receiving'],
        ],
        [
            'key' => 'zberihannia_tovaru',
            'name' => ['uk' => 'Зберігання товару', 'en' => 'Goods storage'],
        ],
        [
            'key' => 'vidvantazhennia_tovaru',
            'name' => ['uk' => 'Відвантаження товару', 'en' => 'Goods shipment'],
        ],
        [
            'key' => 'komplektatsiia_tovaru',
            'name' => ['uk' => 'Комплектація товару', 'en' => 'Order picking'],
        ],
        [
            'key' => 'stikeruvannia_tovaru',
            'name' => ['uk' => 'Стікерування товару', 'en' => 'Goods labeling'],
        ],
        [
            'key' => 'kopakinh_tovaru',
            'name' => ['uk' => 'Копакінг товару', 'en' => 'Co-packing'],
        ],
        [
            'key' => 'krosdok',
            'name' => ['uk' => 'Кросдок', 'en' => 'Cross-docking'],
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
            ServiceCategories::updateOrCreate(
                ['key' => $item['key']],
                ['name' => $item['name']]
            );
        }
    }
}
