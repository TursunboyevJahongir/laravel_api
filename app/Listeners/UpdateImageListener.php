<?php

namespace App\Listeners;


use App\Services\ResourceService;
use App\Events\UpdateImage;

class UpdateImageListener
{
    public function __construct(protected ResourceService $resource) { }

    public function handle(UpdateImage $event)
    {
        $this->resource->updateImage($event->file, $event->relation, $event->path, $event->identifier);
    }
}
