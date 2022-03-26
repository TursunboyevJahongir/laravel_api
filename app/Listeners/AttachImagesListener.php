<?php

namespace App\Listeners;

use Modules\Resource\Contracts\ResourceServiceContract;
use Modules\Resource\Events\AttachImages;

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
        $this->resource->attachImages($event->images, $event->relation, $event->identifier, $event->path);
    }
}
