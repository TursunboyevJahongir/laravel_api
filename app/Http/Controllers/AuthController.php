<?php

namespace App\Http\Controllers;

use App\Core\Http\Controllers\CoreController as Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\UserCreateRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(AuthService $service)
    {
        parent::__construct($service);
    }

    public function register(UserCreateRequest $request): JsonResponse
    {
        $result = $this->service->register($request);

        return $this->responseWith(compact('result'), 201);
    }

    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->service->login($request);

            return $this->responseWith(compact('result'));
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }

    public function refresh(Request $request)
    {
        try {
            $result = $this->service->refresh($request);

            return $this->responseWith(compact('result'));
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->service->logout($request);

            return $this->responseWith(message: __('messages.logout'));
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }

}
