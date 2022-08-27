<?php

namespace App\Listeners;


use App\Services\ResourceService;
use App\Events\UpdateImage;

class UpdateImageListener
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
     * @param UpdateImage $event
     *
     * @return void
     */
    public function handle(UpdateImage $event)
    {
        $this->resource->updateImage($event->file, $event->relation, $event->path, $event->identifier);
    }
}
