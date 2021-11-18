<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\RoleCreateRequest;
use App\Http\Requests\Api\RoleUpdateRequest;
use App\Http\Resources\Api\RoleResource;
use App\Http\Resources\Api\RoleWithPermissionsResource;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends ApiController
{
    public function __construct(
        private RoleService $roleService
    )
    {
    }

    public function index(): JsonResponse
    {
        return $this->success(__('messages.success'), RoleResource::collection($this->roleService->index()));

    }

    public function permissions(): JsonResponse
    {
        return $this->success(__('messages.success'),
            $this->roleService->permissions());

    }

    public function show(string $name): JsonResponse
    {
        return $this->success(__('messages.success'),
            new RoleWithPermissionsResource($this->roleService->show($name)));
    }

    public function create(RoleCreateRequest $request): JsonResponse
    {
        return $this->success(__('messages.success'),
            new RoleWithPermissionsResource($this->roleService->create($request->validated())));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleUpdateRequest $request
     * @param string $name
     * @return JsonResponse
     */
    public function update(RoleUpdateRequest $request, string $name): JsonResponse
    {
        try {
            return $this->success(__('messages.success'),
                new RoleWithPermissionsResource($this->roleService->update($name, $request->validated())));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string $name
     * @return JsonResponse
     */
    public function delete(string $name): JsonResponse
    {
        try {
            $this->roleService->delete($name);
            return $this->success(__('messages.success'));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }
}
