<?php

namespace App\Policies;

use App\Core\Policies\CorePolicy;

class BookPolicy extends CorePolicy
{
    protected string $name = 'book';
}
