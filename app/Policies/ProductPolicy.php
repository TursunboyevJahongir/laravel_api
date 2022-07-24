<?php

namespace App\Policies;

use App\Core\Policies\CorePolicy;

class ProductPolicy extends CorePolicy
{
    protected string $name = 'product';
}
