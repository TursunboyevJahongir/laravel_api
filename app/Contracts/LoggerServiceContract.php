<?php

namespace App\Contracts;

use App\Core\Contracts\CoreServiceContract;

interface LoggerServiceContract extends CoreServiceContract
{
    public function log($response = [], $status = 200, $message = '');
}
