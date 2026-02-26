<?php

namespace Database\Seeders\User;

use App\Models\Dictionaries\UserStatus;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserStatus::updateOrCreate(
            ['key' => 'online'],
            ['name' => ['uk' => 'В системі', 'en' => 'Online']]
        );

        UserStatus::updateOrCreate(
            ['key' => 'on_warehouse'],
            ['name' => ['uk' => 'На складі', 'en' => 'On warehouse']]
        );

        UserStatus::updateOrCreate(
            ['key' => 'offline'],
            ['name' => ['uk' => 'Офлайн', 'en' => 'Offline']]
        );
    }
}
