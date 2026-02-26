<?php

namespace Database\Seeders\Task;

use App\Models\Entities\Task\TaskType;
use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'income' => ['uk' => 'Приймання', 'en' => 'Incoming'],
            'outcome' => ['uk' => 'Відвантаження', 'en' => 'Outgoing'],
            'movement' => ['uk' => 'Внутрішнє переміщення', 'en' => 'Internal movement'],
            'simple' => ['uk' => 'Просте завдання', 'en' => 'Simple task'],
            'control' => ['uk' => 'Контроль', 'en' => 'Control'],
            'picking' => ['uk' => 'Комплектація', 'en' => 'Picking'],
            'uploading' => ['uk' => 'Завантаження', 'en' => 'Loading'],
            'inventory' => ['uk' => 'Інвентаризація', 'en' => 'Inventory'],
        ];
        TaskType::disableCompanySeparation();
        foreach ($types as $key => $name) {
            TaskType::updateOrCreate(
                ['key' => $key],
                [
                    'name' => $name,
                    'is_system' => true
                ]
            );
        }
        TaskType::enableCompanySeparationDisable();
    }
}
