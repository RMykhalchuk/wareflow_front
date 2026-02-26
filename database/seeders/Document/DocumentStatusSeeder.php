<?php

namespace Database\Seeders\Document;

use App\Models\Dictionaries\DocumentStatus;
use Illuminate\Database\Seeder;

class DocumentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentStatus::updateOrCreate(
            ['key' => 'created'],
            ['name' => ['uk' => 'Створено', 'en' => 'Created']]
        );

        DocumentStatus::updateOrCreate(
            ['key' => 'process'],
            ['name' => ['uk' => 'В роботі', 'en' => 'In process']]
        );

        DocumentStatus::updateOrCreate(
            ['key' => 'done'],
            ['name' => ['uk' => 'Проведено', 'en' => 'Done']]
        );

        DocumentStatus::updateOrCreate(
            ['key' => 'canceled'],
            ['name' => ['uk' => 'Скасовано', 'en' => 'Canceled']]
        );
    }
}
