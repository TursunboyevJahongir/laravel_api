<?php

namespace App\Policies;

use App\Core\Policies\CorePolicy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class RolePolicy extends CorePolicy
{
    use HandlesAuthorization;

    protected string $name = 'role';

    public function updateDelete(User $user, Model $model)
    {
        if (!hasRole($model, $user) && $model->name === "superadmin") {
            return Response::deny(__('messages.not_access'));
        }
    }
}
