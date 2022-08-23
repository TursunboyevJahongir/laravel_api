<?php

namespace App\Contracts;

use App\Core\Contracts\CoreRepositoryContract;
use Spatie\Permission\Models\Permission;

interface PermissionRepositoryContract extends CoreRepositoryContract
{
    public function findByName(string $name): mixed;

    public function findPermissionsByIds(array $permissionIds): mixed;

    public function attachToAdmin(Permission|int|string $permission);
}
