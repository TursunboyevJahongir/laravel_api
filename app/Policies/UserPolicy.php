<?php

namespace App\Policies;

use App\Core\Policies\CorePolicy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class UserPolicy extends CorePolicy
{
    use HandlesAuthorization;

    protected string $name = 'user';

    public function update(User $user, Model $model)
    {
        return hasPermission('update-user', $user) || $user->id !== $model->id
            ? Response::allow()
            : Response::deny(__('messages.not_access'));
    }

    public function delete(User $user, Model $model)
    {
        return (hasPermission('update-user', $user) ||
            !hasRole(user: $model) ||
            $user->id !== $model->id)
            ? Response::allow()
            : Response::deny(__('messages.not_access'));
    }
}
