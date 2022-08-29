<?php

namespace App\Http\Controllers;

use App\Core\Http\Controllers\CoreController as Controller;
use App\Core\Http\Requests\GetAllFilteredRecordsRequest;
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

    public function profile(GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $user = $this->service->show(auth()->user(), $request);

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

    public function index(GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $users = $this->service->index($request);

        return $this->responseWith(compact('users'));
    }

    public function show(User $user, GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $user = $this->service->show($user, $request);

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
