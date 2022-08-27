<?php

namespace App\Listeners;


use App\Services\ResourceService;
use App\Events\UpdateFile;

class UpdateFileListener
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
     * @param UpdateFile $event
     *
     * @return void
     */
    public function handle(UpdateFile $event)
    {
        $this->resource->updateFile($event->file, $event->relation, path: $event->path, identifier: $event->identifier);
    }
}
