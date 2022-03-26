<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserServiceContract;
use App\Core\Http\Controllers\BaseController as Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegistrationRequest;
use App\Http\Resources\Api\UserWithAuthResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(AuthService $service)
    {
        parent::__construct($service);
    }

    public function registration(RegistrationRequest $request): JsonResponse
    {
        $user = $this->service->register($request->validated());

        return $this->responseWith(compact('user'), 201);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            return $this->responseWith($this->service->login($request));
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }

    }

    public function refresh(Request $request)
    {
        return $this->success(__('messages.success'), $this->service->refresh($request));
        return $this->responseWith(message: __('messages.logout'));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->responseWith(message: __('messages.logout'));
    }

    protected function respondWithToken(string $token)
    {
        return $this->responseWith(['access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('sanctum.expiration'),
                'user' => auth()->user()->load('roles', 'avatar')
            ]);
    }

}
