<?php

namespace App\Contracts;

use App\Core\Contracts\CoreRepositoryContract;
use App\Models\Role;
use Illuminate\Database\Eloquent\{Model};

interface RoleRepositoryContract extends CoreRepositoryContract
{
    public function findByName(string $name): mixed;

    /**
     * Assign permission to Role
     *
     * @param Model $role
     * @param int|string $permission
     *
     * @return mixed
     */
    public function givePermissionTo(Model $role, int|string $permission): mixed;

    /**
     * Revoke permission from Role
     *
     * @param Model $role
     * @param int|string $permission
     *
     * @return mixed
     */
    public function revokePermissionTo(Model $role, int|string $permission): mixed;

    /**
     * Sync Role permissions
     *
     * @param Role $role
     * @param array $permissions
     *
     * @return mixed
     */
    public function syncRolePermissions(Model $role, array $permissions): mixed;
}
