<?php

namespace App\Observers;

use App\Events\DestroyFiles;
use App\Models\User;

class UserObserver
{
    public function deleting(User $user)
    {
        DestroyFiles::dispatch($user->avatar->id);
    }
}
