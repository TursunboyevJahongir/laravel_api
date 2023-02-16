<?php

namespace App\Listeners;

use App\Services\ResourceService;
use App\Events\AttachImages;

class AttachImagesListener
{
    public function __construct(protected ResourceService $resource) { }

    public function handle(AttachImages $event)
    {
        $this->resource->attachImages($event->images, $event->relation, $event->path, $event->identifier);
    }
}
