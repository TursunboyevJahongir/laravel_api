<?php

namespace App\Listeners;

use App\Events\LoggerEvent;
use App\Services\LoggerService;

class LoggerListener
{
    public function __construct(protected LoggerService $service) { }

    public function handle(LoggerEvent $event)
    {
        $this->service->log($event->response, $event->response_status, $event->response_message);
    }
}
