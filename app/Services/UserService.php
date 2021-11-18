<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
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
