<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        //
    }

    public function view(User $user, User $model): bool
    {
        //
    }

    public function create(User $user): bool
    {
        //
    }

    public function update(User $user, User $model)
    {
        return $user->hasRole('superadmin') || $user->id === $model->id
            ? Response::deny(__('messages.cannot_change_admin'))
            : Response::allow();
    }

    public function delete(User $user, User $model)
    {
        return $user->hasRole('superadmin') || $user->id === $model->id
            ? Response::deny(__('messages.cannot_change_admin'))
            : Response::allow();
    }

    public function restore(User $user, User $model): bool
    {
        //
    }

    public function forceDelete(User $user, User $model): bool
    {
        //
    }
}
