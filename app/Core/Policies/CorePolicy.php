<?php

namespace App\Core\Policies;

use App\Core\Models\CoreModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CorePolicy
{
    use HandlesAuthorization;

    protected string $name;

    public function viewAny(User $user): Response
    {
        return hasPermission("read-{$this->name}", $user)
            ? Response::allow()
            : Response::deny(__('messages.not_access'));
    }

    public function view(User $user, CoreModel $model): Response
    {
        return hasPermission("read-{$this->name}", $user)
            ? Response::allow()
            : Response::deny(__('messages.not_access'));
    }

    public function create(User $user): Response
    {
        return hasPermission("create-{$this->name}", $user)
            ? Response::allow()
            : Response::deny(__('messages.not_access'));
    }

    public function update(User $user, CoreModel $model)
    {
        return hasPermission("update-{$this->name}", $user)
            ? Response::allow()
            : Response::deny(__('messages.not_access'));
    }

    public function delete(User $user, CoreModel $model)
    {
        return hasPermission("delete-{$this->name}", $user)
            ? Response::allow()
            : Response::deny(__('messages.not_access'));
    }
}
