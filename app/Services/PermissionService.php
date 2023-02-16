<?php

namespace App\Services;

use App\Core\Services\CoreService;
use App\Http\Requests\Role\CheckPermissionsRequest;
use App\Models\User;
use App\Repositories\PermissionRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PermissionService extends CoreService
{
    public function __construct(PermissionRepository $repository)
    {
        parent::__construct($repository);
    }

    public function hasAllPermissions(CheckPermissionsRequest $request, User $user = null): bool
    {
        $user = $user ?? auth()->user();

        return $user->hasAllPermissions($request->permission);
    }

    public function create(Validator|FormRequest $request): mixed
    {
        $permission = parent::create($request);
        $this->repository->attachToAdmin($permission);

        return $permission;
    }
}
