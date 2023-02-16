<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;

class UpdateImage
{
    use SerializesModels, Dispatchable;

    public function __construct(
        public UploadedFile $file,
        public $relation,
        public string $path = 'files',
        public string|null $identifier = null
    ) {
    }
}
