<?php


namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserService
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

    public function update(array $data, User $user): User
    {
        if (isset($data['current_password'])) {
            if (!Hash::check($data['current_password'], $user->password))
                throw new \Exception(__('messages.invalid_password'));
            $data['password'] = Hash::make($data['new_password']);
        }
        $user->update($data);
        return $user;
    }

    public function updateProfile(array $data)
    {
        !isset($data['new_password']) ?: $data['password'] = Hash::make($data['new_password']);
        auth()->user()->update($data);
    }
}
