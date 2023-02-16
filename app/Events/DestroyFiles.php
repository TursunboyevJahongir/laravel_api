<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DestroyFiles
{
    use SerializesModels, Dispatchable;

    public function __construct(public $imageIds = [])
    {
    }
}
