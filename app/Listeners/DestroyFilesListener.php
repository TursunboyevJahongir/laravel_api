<?php

namespace App\Listeners;


use App\Services\ResourceService;
use App\Events\DestroyFiles;

class DestroyFilesListener
{
    public function __construct(protected ResourceService $resource)
    {
        //
    }

    public function handle(DestroyFiles $event)
    {
        $this->resource->destroyImages($event->imageIds);
    }
}
