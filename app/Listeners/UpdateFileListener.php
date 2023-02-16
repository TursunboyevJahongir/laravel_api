<?php

namespace App\Listeners;

use App\Services\ResourceService;
use App\Events\UpdateFile;

class UpdateFileListener
{
    public function __construct(protected ResourceService $resource) { }

    public function handle(UpdateFile $event)
    {
        $this->resource->updateFile($event->file, $event->relation, path: $event->path, identifier: $event->identifier);
    }
}
