<?php

namespace App\Contracts;


use App\Core\Contracts\CoreServiceContract;
use App\Http\Requests\Api\Role\CheckPermissionsRequest;
use App\Models\User;

interface PermissionServiceContract extends CoreServiceContract
{
    public function hasAllPermissions(CheckPermissionsRequest $request, ?User $user): bool;
}
