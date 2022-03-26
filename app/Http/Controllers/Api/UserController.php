<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserServiceContract;
use App\Http\Requests\Api\UserCreateRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Requests\GetAllFilteredRecordsRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Core\Http\Controllers\BaseController as Controller;

class UserController extends Controller
{

    public function __construct(UserServiceContract $service)
    {
        parent::__construct($service);
    }

    public function me(GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $user = $this->service->show(auth()->user(), $request);
        return $this->responseWith(compact('user'));
    }

    public function updateProfile(UserUpdateRequest $request): JsonResponse
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
        $users = $this->service->get($request);

        return $this->responseWith(['users' => $users]);
    }

    public function show(User $user, GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $user = $this->service->show($user, $request);

        return $this->responseWith(compact('user'));
    }


    public function create(UserCreateRequest $request): JsonResponse
    {
        try {
            $user = $this->service->create($request);

            return $this->responseWith(compact('user'), 201);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }

    public function update(User $user,UserUpdateRequest $request): JsonResponse
    {
        try {
            $this->service->update($user, $request);

            return $this->responseWith(code: 204);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function delete(User $user): JsonResponse
    {
        try {
            $this->authorize('delete', $user);
            $this->service->delete($user);

            return $this->responseWith(code: 204);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }
}
