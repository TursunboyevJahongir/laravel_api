<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::findByName('superadmin')->syncPermissions(Permission::all());
        $manager = Role::findByName('moderator');
        $manager->syncPermissions(
            ['read-user',
             'create-user',
             'update-user',
             'read-category',
             'create-category',
             'update-category',]
        );
    }
}
