<?php

namespace App\Providers;

use App\Observers\{CategoryObserver, ProductObserver, UserObserver};
use App\Models\{Category, Product, User};
use App\Listeners\{AttachImagesListener, DestroyFilesListener, LoggerListener, UpdateFileListener, UpdateImageListener};
use App\Events\{AttachImages, DestroyFiles, LoggerEvent, UpdateFile, UpdateImage};
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        Category::class => [CategoryObserver::class],
        User::class     => [UserObserver::class],
        Product::class  => [ProductObserver::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void { }
}
