<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public static function Register(array $data): ?User
    {
        $data['password'] = Hash::make($data['password']);
        /**
         * @var User|null $user
         */
        $user = User::create($data);
        $token = $user->createToken('user_' . $data['email'])->plainTextToken;
        $user->auth_token = $token;
        return $user;
    }

    public static function Login(array $data)
    {
        $user = User::query()->where('email', $data['email'])->first();
        if ($user === null || !Hash::check($data['password'], $user->password)) {
            throw new \Exception(__('messages.invalid_email'), 401);
        }
        $token = $user->createToken($user->email);
        $user->auth_token = $token->plainTextToken;
        return $user;
    }

    public static function update(array $data, User $user): User
    {
        if (isset($data['current_password'])) {
            if (!Hash::check($data['current_password'], $user->password))
                throw new \Exception(__('messages.invalid_password'));
            $data['password'] = Hash::make($data['new_password']);
        }
        $user->update($data);
        return $user;
    }
}
