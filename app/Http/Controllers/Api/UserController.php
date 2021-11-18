<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\UserCreateRequest;
use App\Http\Requests\Api\UserUpdateFromAdminRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\Api\AllAdminResource;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\UserResource;
use App\Http\Resources\Api\UserWithRoleResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{

    public function __construct(private UserService $service)
    {
    }

    public function me(): JsonResponse
    {
        return $this->success(__('messages.success'), new UserResource(auth()->user()));
    }

    public function updateProfile(UserUpdateRequest $request): JsonResponse
    {
        try {
            $this->service->updateProfile($request->validated());
            return $this->success(__('messages.success'), new UserResource(Auth::user()));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }

    public function index(Request $request): JsonResponse
    {
        $size = $request->get('per_page') ?? config('app.per_page');
        $role = $request->role ?? null;
        try {
            return $this->success(__('messages.success'),
                new PaginationResourceCollection($this->service->index($size, $role), UserWithRoleResource::class));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }

    public function show(User $id): JsonResponse
    {
        return $this->success(__('messages.success'), new UserResource($id));
    }


    public function create(UserCreateRequest $request): JsonResponse
    {
        try {
            return $this->success(__('messages.success'),
                new UserResource($this->service->create($request->validated())));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }

    }

    public function update(UserUpdateFromAdminRequest $request): JsonResponse
    {
        $user = User::find($request->id);
        try {
            $this->service->update($user, $request->validated());
            return $this->success(__('messages.success'), new UserResource($user));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }

    }

    /**
     * @param User $id
     * @return JsonResponse
     */
    public function delete(User $id): JsonResponse
    {
        try {
            return $this->success(__('messages.user_deleted', ['attribute' => $this->service->delete($id)]));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }
}
