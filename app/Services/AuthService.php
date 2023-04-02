<?php


namespace App\Services;

use App\Core\Helpers\ResponseCode;
use App\Repositories\UserRepository;
use App\Core\Services\CoreService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService extends CoreService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function register(FormRequest $request)
    {
        $user = $this->repository->create($request->validated());

        return $this->repository->generateRefreshToken($user);
    }

    public function login(FormRequest $request)
    {
        $user = $this->repository->firstBy($request['phone'], 'phone', fail: false);
        if (!$user || !Hash::check($request['password'], $user->password)) {
            throw new \Exception(__('auth.password'), ResponseCode::HTTP_UNAUTHORIZED);
        }

        return $this->repository->generateRefreshToken($user);
    }

    public function refresh(Request $request)
    {
        $token = $this->repository->firstByRefreshToken($request);
        if ($token) {
            if ($token->refresh_expired_at->greaterThan(now())) {
                $user = $token->user;
                $token->delete();

                return $this->repository->generateRefreshToken($user);
            }
            $this->repository->delete($token);
        }

        throw new \Exception('Unauthenticated', ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request)
    {
        $token = $this->repository->firstByToken($request);
        if (!$token) {
            return throw new \Exception('Unauthenticated', ResponseCode::HTTP_UNAUTHORIZED);
        }
        auth()->user()->currentAccessToken()?->delete();

        $token->delete();
    }
}
