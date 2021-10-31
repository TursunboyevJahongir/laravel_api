<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\api\Auth\LoginRequest;
use App\Http\Requests\api\Auth\RegistrationRequest;
use App\Http\Resources\Api\UserWithAuthResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function registration(RegistrationRequest $request): JsonResponse
    {
        return $this->success(__('sms.success'), new UserWithAuthResource(UserService::Register($request->validated())));
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            return $this->success(__('sms.success'), new UserWithAuthResource(UserService::Login($request->validated())));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }

    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        // auth()->user()->tokens()->delete();

        return $this->success(__('messages.success'));
    }
}
