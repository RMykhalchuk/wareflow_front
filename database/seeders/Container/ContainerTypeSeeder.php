<?php

namespace Database\Seeders\Container;

use App\Models\Entities\Container\ContainerType;
use Illuminate\Database\Seeder;

class ContainerTypeSeeder extends Seeder
{
    public $items = [
        ['key' => 'type_1', 'name' => ['uk' => 'Європалета', 'en' => 'Euro pallet']],
        ['key' => 'type_2', 'name' => ['uk' => 'Американська палета', 'en' => 'American pallet']],
        ['key' => 'type_3', 'name' => ['uk' => 'Пластиковий контейнер', 'en' => 'Plastic container']],
        ['key' => 'type_4', 'name' => ['uk' => 'Коробка', 'en' => 'Box']],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items as $item) {
            ContainerType::updateOrCreate(
                ['key' => $item['key']],
                ['name' => $item['name']]
            );
        }
    }
}
