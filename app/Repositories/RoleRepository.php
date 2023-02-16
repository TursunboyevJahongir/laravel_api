<?php

namespace App\Repositories;

use App\Core\Repositories\CoreRepository;
use App\Models\Role;
use Illuminate\Database\Eloquent\{Builder, Model};

class RoleRepository extends CoreRepository
{
    /**
     * @param Role $model
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(Role $model, protected PermissionRepository $permissionRepository)
    {
        parent::__construct($model);
    }

    public function availability(Builder|Model $query): void
    {
        $query->when(notSystem(),
            function (Builder $query) {
                $query->whereDoesntHave('permissions', function ($query) {
                    $query->where('name', 'system');
                })->whereNotIn('name', ['owner', 'demo']);
            });
    }

    public function givePermissionTo(Model $role, int|string $permission): mixed
    {
        return $role->givePermissionTo($permission);
    }

    public function revokePermissionTo(Model $role, int|string $permission): mixed
    {
        return $role->revokePermissionTo($permission);
    }

    /**
     * Sync Role permissions
     */
    public function syncRolePermissions(Model $role, array $permissions): mixed
    {
        return $role->syncPermissions($permissions);
    }
}
