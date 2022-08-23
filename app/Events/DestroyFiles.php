<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DestroyFiles
{
    use SerializesModels, Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public $imageIds)
    {
    }
}
