<?php

namespace App\Providers;

use App\Events\LoggerEvent;
use App\Events\UpdateFile;
use App\Listeners\LoggerListener;
use App\Listeners\UpdateFileListener;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\AttachImages;
use App\Events\DestroyFiles;
use App\Events\UpdateImage;
use App\Listeners\AttachImagesListener;
use App\Listeners\DestroyFilesListener;
use App\Listeners\UpdateImageListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        LoggerEvent::class  => [
            LoggerListener::class,
        ],
        UpdateImage::class  => [
            UpdateImageListener::class,
        ],
        UpdateFile::class   => [
            UpdateFileListener::class,
        ],
        AttachImages::class => [
            AttachImagesListener::class,
        ],
        DestroyFiles::class => [
            DestroyFilesListener::class,
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
