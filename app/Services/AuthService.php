<?php


namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public static function Register(array $data): ?User
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->assignRole('customer');
        $token = $user->createToken('user_' . $data['phone'])->plainTextToken;
        $user->auth_token = self::getToken($token);
        return $user;
    }

    public static function Login(array $data)
    {
        $user = User::query()->where('phone', $data['phone'])->first();
        if ($user === null || !Hash::check($data['password'], $user->password)) {
            throw new \Exception(__('messages.invalid_phone'), 401);
        }
        $token = $user->createToken($user->phone)->plainTextToken;
        $user->auth_token = self::getToken($token);
        return $user;
    }

    public function refresh(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return self::getToken($request->user()->createToken('api')->plainTextToken);
    }

    public static function getToken(string $token)
    {
        return
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => 60 * 24 * 7,
                'expires_date' => Carbon::now()->addDays(7)->format('d-m-Y H:i')
            ];
    }
}