<?php

namespace App\Listeners;


use App\Contracts\ResourceServiceContract;
use App\Events\DestroyFiles;

class DestroyFilesListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(protected ResourceServiceContract $resource)
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
