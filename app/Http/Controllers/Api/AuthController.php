<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegistrationRequest;
use App\Http\Resources\Api\UserWithAuthResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    public function __construct(private AuthService $service)
    {
    }

    public function registration(RegistrationRequest $request): JsonResponse
    {
        return $this->success(__('messages.success'), new UserWithAuthResource($this->service->Register($request->validated())));
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            return $this->success(__('messages.success'), new UserWithAuthResource($this->service->Login($request->validated())));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }

    }

    public function refresh(Request $request)
    {
        return $this->success(__('messages.success'), $this->service->refresh($request));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        // auth()->user()->tokens()->delete();

        return $this->success(__('messages.success'));
    }

}
