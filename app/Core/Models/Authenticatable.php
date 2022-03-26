<?php

namespace App\Core\Models;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class Authenticatable extends CoreModel implements
        AuthenticatableContract,
        AuthorizableContract,
        CanResetPasswordContract
{
    use AuthenticatableTrait, Authorizable, CanResetPassword, MustVerifyEmail;
}
