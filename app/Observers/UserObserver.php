<?php

namespace App\Observers;

use App\Events\DestroyFiles;
use App\Events\UpdateImage;
use App\Models\User;

class UserObserver
{
    public function deleting(User $user)
    {
        DestroyFiles::dispatch($user->avatar->id);
    }

    public function created(User $user)
    {
        (request('roles') && !in_array('superadmin', request('roles'))) ?
            $user->syncRoles(request('roles')) : $user->syncRoles(['customer']);

        if (request()->hasFile('avatar')) {
            UpdateImage::dispatch(request('avatar'), $user->avatar());
        }
    }

    public function updating(User $user)
    {
        if (request('roles')) {
            $user->syncRoles(request('roles'));
        }
    }
    public function updated(User $user)
    {
        if (request()->hasFile('avatar')) {
            UpdateImage::dispatch(request('avatar'), $user->avatar());
        }
    }
}
