<?php

namespace Database\Seeders\Document;

use App\Models\Dictionaries\DoctypeStatus;
use Illuminate\Database\Seeder;

class DocumentTypeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DoctypeStatus::updateOrCreate(
            ['key' => 'archieve'],
            ['name' => ['uk' => 'Архів', 'en' => 'Archive']]
        );

        DoctypeStatus::updateOrCreate(
            ['key' => 'system'],
            ['name' => ['uk' => 'Системний', 'en' => 'System']]
        );

        DoctypeStatus::updateOrCreate(
            ['key' => 'draft'],
            ['name' => ['uk' => 'Чернетка', 'en' => 'Draft']]
        );
    }
}
