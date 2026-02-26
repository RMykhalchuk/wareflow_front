<?php

namespace Database\Seeders\TransportPlanning;

use App\Models\Dictionaries\TransportPlanningStatus;
use Illuminate\Database\Seeder;

class TransportPlanningStatusesSeeder extends Seeder
{
    public $items = [
        [
            'key' => 'approval_price',
            'name' => ['uk' => 'На затвердженні ціни', 'en' => 'Price approval'],
        ],
        [
            'key' => 'approved',
            'name' => ['uk' => 'Затверджений', 'en' => 'Approved'],
        ],
        [
            'key' => 'before_downloading',
            'name' => ['uk' => 'До завантаження', 'en' => 'Before loading'],
        ],
        [
            'key' => 'loaded',
            'name' => ['uk' => 'Завантажений', 'en' => 'Loaded'],
        ],
        [
            'key' => 'in_the_way',
            'name' => ['uk' => 'В дорозі', 'en' => 'On the way'],
        ],
        [
            'key' => 'in_the_distribution_center',
            'name' => ['uk' => 'В розподільчому центрі', 'en' => 'In distribution center'],
        ],
        [
            'key' => 'delivery_in_progress',
            'name' => ['uk' => 'Триває постачання', 'en' => 'Delivery in progress'],
        ],
        [
            'key' => 'delivered',
            'name' => ['uk' => 'Доставлено', 'en' => 'Delivered'],
        ],
        [
            'key' => 'delivered_with_a_delay',
            'name' => ['uk' => 'Доставлено з затримкою', 'en' => 'Delivered with delay'],
        ],
        [
            'key' => 'delivered_damaged',
            'name' => ['uk' => 'Доставлено з пошкодженням', 'en' => 'Delivered damaged'],
        ],
        [
            'key' => 'paid',
            'name' => ['uk' => 'Оплачено', 'en' => 'Paid'],
        ],
        [
            'key' => 'end_the_trip',
            'name' => ['uk' => 'Завершити рейс', 'en' => 'End the trip'],
        ],
        [
            'key' => 'cancel_the_trip',
            'name' => ['uk' => 'Скасувати рейс', 'en' => 'Cancel the trip'],
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
            TransportPlanningStatus::updateOrCreate(
                ['key' => $item['key']],
                ['name' => $item['name']]
            );
        }
    }
}
