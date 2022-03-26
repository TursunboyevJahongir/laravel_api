<?php

namespace App\Listeners;

use Modules\Resource\Contracts\ResourceServiceContract;
use Modules\Resource\Events\DestroyImages;

class DestroyImagesListener
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
     * @param DestroyImages $event
     *
     * @return void
     */
    public function handle(DestroyImages $event)
    {
        $this->resource->destroyImages($event->imageIds);
    }
}
