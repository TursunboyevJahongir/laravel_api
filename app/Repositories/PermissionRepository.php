<?php

namespace App\Repositories;

use App\Contracts\PermissionRepositoryContract;
use App\Core\Repositories\CoreRepository;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends CoreRepository implements PermissionRepositoryContract
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    /**
     * Find permissions by given ids
     *
     * @param array $permissionIds
     *
     * @return mixed
     */
    public function findPermissionsByIds(array $permissionIds): mixed
    {
        return $this->model->whereIn('id', $permissionIds)->get();
    }

    public function attachToAdmin(Permission|int|string $permission)
    {
        Role::findByName('superadmin')->givePermissionTo($permission);
    }

    public function findByName(string $name): mixed
    {
        return $this->availability($this->model)->whereName($name)->firstOrFail();
    }

    public function role(
        Builder $query,
        $role = null,
    ) {
        return $query->when($role, function (Builder $query) use ($role) {
            $query->whereHas('roles', function (Builder $query) use ($role) {
                if (is_array($role)) {
                    $query->whereIn('name', $role);
                } else {
                    $query->where('name', $role);
                }
            });
        });
    }
}
