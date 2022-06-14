<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoggerEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public $response = [],
        public $response_status = 200,
        public $response_message = ''
    ) {
    }
}
