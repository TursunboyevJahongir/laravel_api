<?php

namespace App\Contracts;

use App\Core\Contracts\CoreServiceContract;
use App\Http\Requests\Api\{Role\PermissionRequest, Role\RolesPermissionsRequest, Role\SyncPermissionsRequest};
use App\Models\Role;

interface RoleServiceContract extends CoreServiceContract
{
    public function revokePermissionTo(Role $role, PermissionRequest $request): mixed;

    public function syncPermissions(Role $role, SyncPermissionsRequest $request): mixed;
}
