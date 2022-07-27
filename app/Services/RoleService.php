<?php

namespace App\Services;

use App\Contracts\RoleRepositoryContract;
use App\Contracts\RoleServiceContract;
use App\Core\Services\CoreService;
use App\Http\Requests\Api\Role\PermissionRequest;
use App\Http\Requests\Api\Role\SyncPermissionsRequest;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class RoleService extends CoreService implements RoleServiceContract
{
    public function __construct(RoleRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Give permission to Role
     *
     * @param Role $role
     * @param PermissionRequest $request
     *
     * @return Role
     */
    public function givePermissionTo(Role $role, PermissionRequest $request): Role
    {
        return $this->repository->givePermissionTo($role, $request->get('permission'));
    }

    /**
     * Revoke permission to
     *
     * @param Role $role
     * @param PermissionRequest $request
     *
     * @return mixed
     */
    public function revokePermissionTo(Role $role, PermissionRequest $request): mixed
    {
        return $this->repository->revokePermissionTo($role, $request->get('permission'));
    }

    /**
     * Syn Role permissions
     *
     * @param Role $role
     * @param SyncPermissionsRequest $request
     *
     * @return mixed
     */
    public function syncPermissions(Role $role, SyncPermissionsRequest $request): mixed
    {
        return $this->repository->syncRolePermissions($role, $request->get('permissions'));
    }
}
