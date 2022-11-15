<?php

namespace App\Http\Controllers;

use App\Core\Http\Controllers\CoreController as Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(UserService $service)
    {
        parent::__construct($service);
        $this->authorizeResource(User::class, 'user');
    }

    public function profile(): JsonResponse
    {
        $user = $this->service->show(auth()->user());

        return $this->responseWith(compact('user'));
    }

    public function updateProfile(ProfileUpdateRequest $request): JsonResponse
    {
        try {
            $this->service->update(auth()->user(), $request);

            return $this->responseWith(code: 204);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }

    public function index(): JsonResponse
    {
        $users = $this->service->index();

        return $this->responseWith(compact('users'));
    }

    public function show(User $user): JsonResponse
    {
        $user = $this->service->show($user);

        return $this->responseWith(compact('user'));
    }

    public function store(UserCreateRequest $request): JsonResponse
    {
        try {
            $user = $this->service->create($request)->loadMissing('roles', 'avatar');

            return $this->responseWith(compact('user'), 201);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }

    public function update(User $user, UserUpdateRequest $request): JsonResponse
    {
        try {
            $this->service->update($user, $request);

            return $this->responseWith(code: 204);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }

    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user);

        return $this->responseWith(code: 204);
    }
}
