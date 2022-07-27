<?php

namespace App\Listeners;


use App\Contracts\ResourceServiceContract;
use App\Events\UpdateFile;
use App\Events\UpdateImage;

class UpdateFileListener
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
     * @param UpdateFile $event
     *
     * @return void
     */
    public function handle(UpdateFile $event)
    {
        $this->resource->updateFile($event->file, $event->relation, path: $event->path, identifier: $event->identifier);
    }
}
