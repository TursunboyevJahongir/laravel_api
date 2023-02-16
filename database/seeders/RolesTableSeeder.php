<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\{Permission, Role};
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\Finder\Finder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roleNames = [
            'superadmin' => ['uz' => 'Administrator', 'ru' => "Администратор", 'en' => "Administrator"],
            'moderator'  => ['uz' => 'moderator', 'ru' => "Модератор", 'en' => "moderator"],
            'owner'      => ['uz' => "Korxona egasi", 'ru' => "Владелец", 'en' => "Owner"],
            'salesman'   => ['uz' => "Sotuvchi", 'ru' => "Продавец", 'en' => "Salesman"],
            'customer'   => ['uz' => "Xaridor", 'ru' => "Покупатель", 'en' => "Customer"],
        ];

        $roles = [];
        foreach ($roleNames as $key => $value) {
            $roles[] = [
                'title'      => json_encode($value),
                'name'       => $key,
                'guard_name' => 'api',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Role::insert($roles);

        $finder = new Finder();
        $path   = app_path('Http/Controllers');
        $finder->in($path)->name('*.php')->notName('AuthController.php');
        $controllerNames = [];
        foreach ($finder as $f) {
            $controllerNames[] = Str::singular(str_replace('Controller.php', '', $f->getFilename()));
        }
        foreach ($controllerNames as $k => $name) {
            try {
                $commonPermissions = ['create', 'read', 'update', 'delete'];
                foreach ($commonPermissions as $key => $permission) {
                    $permissions[$key] = ['name'       => mb_strtolower($permission . '-' . Str::snake($name)),
                                          'guard_name' => 'api',
                                          'created_at' => now(),
                                          'updated_at' => now()];
                }
                Permission::insert($permissions);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        $this->call(PermissionsSeeder::class);
    }
}
