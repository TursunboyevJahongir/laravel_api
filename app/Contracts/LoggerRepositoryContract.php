<?php

namespace App\Contracts;

use App\Core\Contracts\CoreRepositoryContract;

interface LoggerRepositoryContract extends CoreRepositoryContract
{
    public function log($response = [], $status = 200, $message = '');
}
