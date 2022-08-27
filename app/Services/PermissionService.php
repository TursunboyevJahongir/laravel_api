<?php

namespace App\Services;

use App\Core\Services\CoreService;
use App\Http\Requests\Role\CheckPermissionsRequest;
use App\Models\User;
use App\Repositories\PermissionRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class PermissionService extends CoreService
{
    public function __construct(PermissionRepository $repository)
    {
        parent::__construct($repository);
    }

    public function appends(Builder $query)
    {
        $this->repository->role($query, request()->get('role'));
    }

    /**
     * @param CheckPermissionsRequest $request
     * @param User|null $user
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAllPermissions(CheckPermissionsRequest $request, User $user = null): bool
    {
        $user = $user ?? auth()->user();

        return $user->hasAllPermissions($request->permission);
    }

    public function created(Model $model, FormRequest $request): void
    {
        $this->repository->attachToAdmin($model);
    }
}
