<?php

namespace App\Http\Controllers;

use App\Core\Http\Controllers\CoreController as Controller;
use App\Http\Requests\Role\CheckPermissionsRequest;
use App\Http\Requests\Role\PermissionCreateRequest;
use App\Http\Requests\Role\PermissionUpdateRequest;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(PermissionService $service)
    {
        parent::__construct($service);
        $this->authorizeResource(Permission::class, 'permission');
    }

    public function index(): JsonResponse
    {
        $result = $this->service->index();

        return $this->responseWith(compact('result'));
    }

    public function store(PermissionCreateRequest $request): JsonResponse
    {
        $permission = $this->service->create($request);

        return $this->responseWith(compact('permission'), 201);
    }

    public function show(Permission $permission): JsonResponse
    {
        $permission = $this->service->show($permission);

        return $this->responseWith(compact('permission'));
    }

    public function update(Permission $permission, PermissionUpdateRequest $request): JsonResponse
    {
        $this->service->update($permission, $request);

        return $this->responseWith(code: 204);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        try {
            $this->service->delete($permission);

            return $this->responseWith(code: 204);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage(), logging: true);
        }
    }

    /**
     * List all permissions
     *
     * @param CheckPermissionsRequest $request
     * @param User|null $user
     *
     * @return JsonResponse
     */
    public function hasAllPermissions(CheckPermissionsRequest $request, User $user = null): JsonResponse
    {
        $result = $this->service->hasAllPermissions($request, $user);

        return $this->responseWith(compact('result'));
    }
}
