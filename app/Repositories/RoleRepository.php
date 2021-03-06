<?php

namespace App\Repositories;

use App\Contracts\PermissionRepositoryContract;
use App\Contracts\RoleRepositoryContract;
use App\Core\Repositories\CoreRepository;
use App\Models\Role;
use Illuminate\Database\Eloquent\{Builder, Model};

class RoleRepository extends CoreRepository implements RoleRepositoryContract
{
    /**
     * @param Role $model
     * @param PermissionRepositoryContract $permissionRepository
     */
    public function __construct(Role $model, protected PermissionRepositoryContract $permissionRepository)
    {
        parent::__construct($model);
    }

    public function availability(Builder|Model $query): Builder|Model
    {
        return $query->when(!hasPermission("system") || !hasRole('superadmin'),
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
     *
     * @param Model $role
     * @param array $permissions
     *
     * @return mixed
     */
    public function syncRolePermissions(Model $role, array $permissions): mixed
    {
        return $role->syncPermissions($permissions);
    }

    /**
     * Find Role by name
     *
     * @param string $name Role name
     *
     * @return mixed
     */
    public function findByName(string $name): mixed
    {
        return $this->availability($this->model)->whereName($name)->firstOrFail();
    }
}
