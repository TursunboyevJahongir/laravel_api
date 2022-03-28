<?php


namespace App\Services;

use App\Contracts\UserRepositoryContract;
use App\Core\Services\CoreService;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService extends CoreService
{
    public function __construct(UserRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    public function register(array $data)
    {
        $user = DB::transaction(function () use ($data) {
            $user = $this->repository->create($data);
            $this->repository->syncRoleToUser($user, 'customer');
            return $user;
        });

        return $this->getToken($user);
    }

    public function login(FormRequest $request)
    {
        $data = $request->validated();
        $user = $this->repository->findByPhone($data['phone']);
        if ($user && Hash::check($data['password'], $user->password)) {
            throw new \Exception(__('auth.failed'), 401);
        }
        return $this->getToken($user);
    }

    public function refresh(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->getToken($request->user()->createToken('api')->plainTextToken);
    }

    public static function getToken(User $user)
    {
        $token = $user->createToken('user_' . $user->phone)->plainTextToken;
        return
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('sanctum.expiration'),
                'user' => $user->load('roles', 'avatar'),
            ];
    }
}
