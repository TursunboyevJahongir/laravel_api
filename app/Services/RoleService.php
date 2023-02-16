<?php

namespace App\Services;

use App\Core\Services\CoreService;
use App\Http\Requests\Role\PermissionRequest;
use App\Http\Requests\Role\SyncPermissionsRequest;
use App\Models\Role;
use App\Repositories\RoleRepository;

class RoleService extends CoreService
{
    public function __construct(RoleRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Give permission to Role
     */
    public function givePermissionTo(Role $role, PermissionRequest $request): Role
    {
        return $this->repository->givePermissionTo($role, $request->get('permission'));
    }

    /**
     * Revoke permission to
     */
    public function revokePermissionTo(Role $role, PermissionRequest $request): mixed
    {
        return $this->repository->revokePermissionTo($role, $request->get('permission'));
    }

    /**
     * Sync Role permissions
     */
    public function syncPermissions(Role $role, SyncPermissionsRequest $request): mixed
    {
        return $this->repository->syncRolePermissions($role, $request->get('permissions'));
    }
}
