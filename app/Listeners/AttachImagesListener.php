<?php

namespace App\Listeners;

use App\Contracts\ResourceServiceContract;
use App\Events\AttachImages;

class AttachImagesListener
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
     * @param AttachImages $event
     *
     * @return void
     */
    public function handle(AttachImages $event)
    {
        $this->resource->attachImages($event->images, $event->relation, $event->path, $event->identifier);
    }
}
