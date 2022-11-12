<?php

namespace App\Repositories;

use App\Core\Repositories\CoreRepository;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends CoreRepository
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function availability(Builder|Model $query): void
    {
        $query->when(notSystem(),
            function (Builder $query) {
                $query->whereIn('name', auth()->user()->getAllPermissions()->pluck('name'));
            });
    }

    public function appends(Builder $query): void
    {
        $this->role($query, request('role'));
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
        $this->firstBy('superadmin', 'name', Role::query())->givePermissionTo($permission);
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
