<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function index($size, $role = null): LengthAwarePaginator
    {
        return User::query()
            ->when(isset($role), function ($query) use ($role) {
                return $query->whereHas('roles', function ($query) use ($role) {
                    $query->where('name', $role);
                });
            })
            ->paginate($size);
    }

    public function updateProfile(array $data)
    {
        !isset($data['new_password']) ?: $data['password'] = Hash::make($data['new_password']);
        auth()->user()->update($data);
    }


    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        if (isset($data['roles']) && !in_array('superadmin', $data['roles']))
            $user->assignRole($data['roles']);
        return $user;
    }

    public function update(User $user, array $data)
    {
        $data['password'] = Hash::make($data['password']);
        if (isset($data['roles']))
            $user->syncRoles($data['roles']);
        $user->update($data);
    }

    public function delete(User $id)
    {
        if ($id->id === Auth::id())
            throw new \Exception(__('messages.fail'), 403);

        if ($id === 1)
            throw new \Exception(__('messages.cannot_change_superadmin'), 403);
        $name = $id->full_name;
        $id->delete();
        return $name;
    }
}
