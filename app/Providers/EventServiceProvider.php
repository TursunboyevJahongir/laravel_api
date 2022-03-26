<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\AttachImages;
use App\Events\DestroyImages;
use App\Events\UpdateImage;
use App\Listeners\AttachImagesListener;
use App\Listeners\DestroyImagesListener;
use App\Listeners\UpdateImagesListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdateImage::class   => [
            UpdateImagesListener::class
        ],
        AttachImages::class  => [
            AttachImagesListener::class
        ],
        DestroyImages::class => [
            DestroyImagesListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
