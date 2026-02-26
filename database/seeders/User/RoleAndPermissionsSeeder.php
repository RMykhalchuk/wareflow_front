<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RoleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        Role::updateOrCreate(
            [
                'name' => 'super_admin',
                'title' => 'Адміністратор системи',
                'visible' => 0,
                'guard_name' => 'web'
            ]);

        Role::updateOrCreate(
            [
                'name' => 'admin',
                'title' => 'Адміністратор',
                'guard_name' => 'web'
            ]);

        Role::updateOrCreate(
            [
                'name' => 'user',
                'title' => 'Користувач',
                'guard_name' => 'web'
            ]);
    }


}
