<?php

namespace Database\Seeders\Transport;

use App\Models\Dictionaries\DeliveryType;
use Illuminate\Database\Seeder;

class DeliveryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeliveryType::updateOrCreate(
            ['key' => 'own_delivery'],
            ['name' => ['uk' => 'Власна доставка', 'en' => 'Own delivery']]
        );

        DeliveryType::updateOrCreate(
            ['key' => 'hired_transport'],
            ['name' => ['uk' => 'Найманий транспорт', 'en' => 'Hired transport']]
        );
    }
}
