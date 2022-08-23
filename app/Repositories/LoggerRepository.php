<?php

namespace App\Repositories;

use App\Contracts\LoggerRepositoryContract;
use App\Core\Repositories\CoreRepository;
use App\Models\Logger;

class LoggerRepository extends CoreRepository implements LoggerRepositoryContract
{
    /**
     * @param Logger $model
     */
    public function __construct(Logger $model)
    {
        parent::__construct($model);
    }

    public function log($response = [], $status = 200, $message = '')
    {
        $this->create(['ip'               => request()->getClientIp(),
                       'user_agent'       => request()->header('User-Agent'),
                       "user_id"          => auth()->user()?->id,
                       'action'           => request()->route()->getAction(),
                       'uri'              => request()->url(),
                       'method'           => request()->method(),
                       'headers'          => request()->header(),
                       'payload'          => request()->input(),
                       'response_headers' => request()->header(),
                       'response_message' => $message,
                       'response_status'  => $status,
                       'response'         => $response]);
    }
}
