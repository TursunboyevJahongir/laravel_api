<?php

namespace App\Policies;

use App\Core\Policies\CorePolicy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class PermissionPolicy extends CorePolicy
{
    use HandlesAuthorization;

    protected string $name = 'permission';

    public function updateDelete(User $user, Model $model)
    {
        if(!hasRole('superadmin') && !hasPermission($model, $user)){
            return Response::deny(__('messages.not_access'));
        }
    }
}
