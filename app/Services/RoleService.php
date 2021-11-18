<?php


namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function index()
    {
        return Role::get();
    }

    public function permissions(): array
    {
        return Permission::query()->where('guard_name', 'web')->get()->pluck('name')->toArray();
    }

    public function show(string $name): Model|Builder
    {
        return Role::query()->where('name', $name)->firstOrFail();
    }

    public function create(array $data): Model|Builder
    {
        $role = Role::create(['guard_name' => 'web', 'name' => $data['name']]);
        if (isset($data['permissions']))
            $role->syncPermissions($data['permissions']);
        return $role;
    }

    public function update(string $name, array $data): Model|Builder
    {
        if ($name === 'admin') {
            throw new \Exception(__('messages.cannot_change_admin'), 403);
        }
        $role = Role::query()->where('name', $name)->firstOrFail();
        $role->syncPermissions($data['permissions']);
        return $role;
    }

    public function delete(string $name)
    {
        if ($name === 'admin') {
            throw new \Exception(__('messages.cannot_change_admin'), 403);
        }

        if ($name === 'customer') {
            throw new \Exception(__('messages.cannot_delete_using_element'), 403);
        }
        $role = Role::where(['name' => $name])->first();
        $role->delete();
    }
}
