<?php

namespace App\Observers;

use App\Events\DestroyImages;
use App\Models\User;

class UserObserver
{
    public function deleting(User $user)
    {
        DestroyImages::dispatch($user->avatar->id);
    }
}
