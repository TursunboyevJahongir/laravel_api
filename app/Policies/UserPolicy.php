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

    public function updateDelete(User $user, Model $model)
    {
        if (hasRole('superadmin', $model) || $user === $model) {
            return Response::deny(__('messages.not_access'));
        }
    }
}
