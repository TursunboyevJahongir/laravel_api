<?php

namespace Database\Seeders;


use App\Models\User;
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
        $admin = Role::create(['name' => 'admin']);
        Role::create(['name' => 'customer']);
        $manager = Role::create(['name' => 'manager']);
        $content_manager = Role::create(['name' => 'content_manager']);

        Permission::create(['name' => 'read product']);
        Permission::create(['name' => 'create product']);
        Permission::create(['name' => 'update product']);
        $content_manager->syncPermissions(Permission::all());

        Permission::create(['name' => 'delete product']);
        Permission::create(['name' => 'read category']);
        Permission::create(['name' => 'create category']);
        Permission::create(['name' => 'update category']);
        Permission::create(['name' => 'delete category']);
        $manager->syncPermissions(Permission::all());

        Permission::create(['name' => 'read user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'read role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);
        $admin->syncPermissions(Permission::all());

        $first = User::first();

        $first->assignRole($admin);
        $first->save();
    }
}
