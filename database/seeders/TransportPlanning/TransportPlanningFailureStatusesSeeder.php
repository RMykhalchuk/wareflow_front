<?php

namespace Database\Seeders\TransportPlanning;

use App\Models\Dictionaries\TransportPlanningFailureType;
use Illuminate\Database\Seeder;

class TransportPlanningFailureStatusesSeeder extends Seeder
{
    public $items = [
        [
            'key' => 'failure_1',
            'name' => ['uk' => 'Збій 1', 'en' => 'Failure 1'],
        ],
        [
            'key' => 'failure_2',
            'name' => ['uk' => 'Збій 2', 'en' => 'Failure 2'],
        ],
        [
            'key' => 'failure_3',
            'name' => ['uk' => 'Збій 3', 'en' => 'Failure 3'],
        ],
        [
            'key' => 'failure_4',
            'name' => ['uk' => 'Збій 4', 'en' => 'Failure 4'],
        ],
        [
            'key' => 'failure_5',
            'name' => ['uk' => 'Збій 5', 'en' => 'Failure 5'],
        ],
        [
            'key' => 'failure_6',
            'name' => ['uk' => 'Збій 6', 'en' => 'Failure 6'],
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
            TransportPlanningFailureType::updateOrCreate(
                ['key' => $item['key']],
                ['name' => $item['name']]
            );
        }
    }
}
