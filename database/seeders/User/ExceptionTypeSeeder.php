<?php

namespace Database\Seeders\User;

use App\Models\Dictionaries\ExceptionType;
use Illuminate\Database\Seeder;

class ExceptionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExceptionType::updateOrCreate(
            ['key' => 'day_off'],
            ['name' => ['uk' => 'Вихідний', 'en' => 'Day off']]
        );

        ExceptionType::updateOrCreate(
            ['key' => 'hospital'],
            ['name' => ['uk' => 'Лікарняний', 'en' => 'Sick leave']]
        );

        ExceptionType::updateOrCreate(
            ['key' => 'short_day'],
            ['name' => ['uk' => 'Cкорочений день', 'en' => 'Short day']]
        );

        ExceptionType::updateOrCreate(
            ['key' => 'holiday'],
            ['name' => ['uk' => 'Державний вихідний', 'en' => 'Public holiday']]
        );
    }
}
