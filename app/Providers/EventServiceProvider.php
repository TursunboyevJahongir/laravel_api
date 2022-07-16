<?php

namespace App\Providers;

use App\Events\LoggerEvent;
use App\Listeners\LoggerListener;
use App\Models\User;
use App\Observers\UserObserver;
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
        LoggerEvent::class   => [
            LoggerListener::class,
        ],
        UpdateImage::class   => [
            UpdateImagesListener::class,
        ],
        AttachImages::class  => [
            AttachImagesListener::class,
        ],
        DestroyImages::class => [
            DestroyImagesListener::class,
        ],
    ];

    protected $observers = [
        User::class => [UserObserver::class],
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
