<?php

namespace App\Services;

use App\Contracts\LoggerRepositoryContract;
use App\Contracts\LoggerServiceContract;
use App\Core\Services\CoreService;
use App\Jobs\LoggerJob;

class LoggerService extends CoreService implements LoggerServiceContract
{
    public function __construct(
        LoggerRepositoryContract $repository,
    ) {
        parent::__construct($repository);
    }

    public function log($response = [], $status = 200, $message = '')
    {
        LoggerJob::dispatchAfterResponse($response, $status, $message);
    }

}
