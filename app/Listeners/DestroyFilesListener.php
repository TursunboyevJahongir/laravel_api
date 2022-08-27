<?php

namespace App\Listeners;


use App\Services\ResourceService;
use App\Events\DestroyFiles;

class DestroyFilesListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(protected ResourceService $resource)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param DestroyFiles $event
     *
     * @return void
     */
    public function handle(DestroyFiles $event)
    {
        $this->resource->destroyImages($event->imageIds);
    }
}
