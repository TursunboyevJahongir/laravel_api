<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttachImages
{
    use SerializesModels, Dispatchable;

    public function __construct(
        public array $images,
        public $relation,
        public string $path = 'files',
        public string|null $identifier = null
    ) {
    }
}
