<?php

namespace App\Core\Providers;

use App\Core\{
    Contracts\CoreRepositoryContract,
    Contracts\CoreServiceContract,
    Repositories\CoreRepository,
    Services\CoreService
};
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Collection::make(glob(core_path('Macros/*.php')))
            ->mapWithKeys(function ($path) {
                return [$path => pathinfo($path, PATHINFO_FILENAME)];
            })
            ->each(function ($macro, $path) {
                require_once $path;
            });

        $this->app->bind(CoreServiceContract::class, CoreService::class);
        $this->app->bind(CoreRepositoryContract::class, CoreRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }
}
