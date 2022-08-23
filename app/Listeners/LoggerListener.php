<?php

namespace App\Listeners;

class LoggerListener
{
    public function __construct(protected LoggerServiceContract $service) { }

    public function handle(LoggerEvent $event)
    {
        $this->service->log($event->response, $event->response_status, $event->response_message);
    }
}
