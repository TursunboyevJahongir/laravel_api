<?php

namespace App\Jobs;

use App\Repositories\LoggerRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoggerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param array $response
     * @param int $status
     * @param string $message
     */
    public function __construct(
        protected $response = [],
        protected $status = 200,
        protected $message = ''
    ) {
    }

    public function handle(LoggerRepository $repository)
    {
        $repository->log($this->response, $this->status, $this->message);
    }
}
