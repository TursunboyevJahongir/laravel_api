<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\Api\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
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

    public function update(UserUpdateRequest $request): JsonResponse
    {
        try {
            $this->service->updateProfile($request->validated());
            return $this->success(__('messages.success'), new UserResource(Auth::user()));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }
}
