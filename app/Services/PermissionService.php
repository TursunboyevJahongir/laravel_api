<?php

namespace App\Services;

use App\Core\Services\CoreService;
use App\Http\Requests\Role\CheckPermissionsRequest;
use App\Models\User;
use App\Repositories\PermissionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PermissionService extends CoreService
{
    public function __construct(PermissionRepository $repository)
    {
        parent::__construct($repository);
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

    public function created(Model $model, array $data): void
    {
        $this->repository->attachToAdmin($model);
    }
}
