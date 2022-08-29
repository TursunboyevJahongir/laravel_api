<?php

namespace App\Http\Controllers;

use App\Core\Http\Controllers\CoreController as Controller;
use App\Core\Http\Requests\GetAllFilteredRecordsRequest;
use App\Http\Requests\Role\PermissionRequest;
use App\Http\Requests\Role\RoleCreateRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Http\Requests\Role\SyncPermissionsRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\{JsonResponse};


class RoleController extends Controller
{
    public function __construct(RoleService $service)
    {
        parent::__construct($service);
        $this->authorizeResource(Role::class, 'role');
    }

    public function index(GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $roles = $this->service->index($request);

        return $this->responseWith(compact('roles'));
    }

    public function show(Role $role, GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $role = $this->service->show($role, $request);

        return $this->responseWith(compact('role'));
    }

    public function store(RoleCreateRequest $request): JsonResponse
    {
        try {
            $role = $this->service->create($request)->load('permissions');

            return $this->responseWith(compact('role'), 201);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage(), logging: true);
        }
    }

    public function update(Role $role, RoleUpdateRequest $request): JsonResponse
    {
        try {
            $this->service->update($role, $request);

            return $this->responseWith(code: 204);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage(), logging: true);
        }
    }

    public function destroy(Role $role): JsonResponse
    {
        try {
            $this->service->delete($role);

            return $this->responseWith(code: 204);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage(), logging: true);
        }
    }

    /**
     * Add permission to Role
     *
     * @param Role $role
     * @param PermissionRequest $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assignPermission(Role $role, PermissionRequest $request): JsonResponse
    {
        $this->authorize('update-role', $role);
        $this->service->givePermissionTo($role, $request);

        return $this->responseWith(code: 204);
    }

    /**
     * Revoke permission from Role
     *
     * @param Role $role
     * @param PermissionRequest $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function revokePermission(Role $role, PermissionRequest $request): JsonResponse
    {
        $this->authorize('update-role', $role);
        $this->service->revokePermissionTo($role, $request);

        return $this->responseWith(code: 204);
    }

    /**
     * Sync Role permissions
     *
     * @param Role $role
     * @param SyncPermissionsRequest $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function syncPermissions(Role $role, SyncPermissionsRequest $request): JsonResponse
    {
        $this->authorize('update-role', $role);
        $this->service->syncPermissions($role, $request);

        return $this->responseWith(code: 204);
    }
}
