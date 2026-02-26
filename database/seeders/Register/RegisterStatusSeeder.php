<?php

namespace Database\Seeders\Register;

use App\Models\Dictionaries\RegisterStatus;
use Illuminate\Database\Seeder;

class RegisterStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegisterStatus::updateOrCreate(
            ['key' => 'create'],
            ['name' => ['uk' => 'Створено', 'en' => 'Created']]
        );

        RegisterStatus::updateOrCreate(
            ['key' => 'register'],
            ['name' => ['uk' => 'Зареєстровано', 'en' => 'Registered']]
        );

        RegisterStatus::updateOrCreate(
            ['key' => 'apply'],
            ['name' => ['uk' => 'Підтверджено', 'en' => 'Approved']]
        );

        RegisterStatus::updateOrCreate(
            ['key' => 'launch'],
            ['name' => ['uk' => 'Запущено', 'en' => 'Launched']]
        );

        RegisterStatus::updateOrCreate(
            ['key' => 'release'],
            ['name' => ['uk' => 'Поза територією', 'en' => 'Outside territory']]
        );
    }
}
