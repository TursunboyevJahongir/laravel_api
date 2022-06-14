<?php

namespace App\Providers;

use App\Contracts\LoggerRepositoryContract;
use App\Contracts\LoggerServiceContract;
use App\Contracts\ResourceRepositoryContract;
use App\Contracts\ResourceServiceContract;
use App\Contracts\UserRepositoryContract;
use App\Contracts\UserServiceContract;
use App\Core\Contracts\CoreRepositoryContract;
use App\Core\Contracts\CoreServiceContract;
use App\Core\Repositories\CoreRepository;
use App\Core\Services\CoreService;
use App\Repositories\LoggerRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\UserRepository;
use App\Services\LoggerService;
use App\Services\ResourceService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        $this->app->bind(CoreServiceContract::class, CoreService::class);
        $this->app->bind(CoreRepositoryContract::class, CoreRepository::class);
        #biding to here

        $this->app->bind(UserServiceContract::class, UserService::class);
        $this->app->bind(ResourceServiceContract::class, ResourceService::class);
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(ResourceRepositoryContract::class, ResourceRepository::class);
        $this->app->bind(LoggerServiceContract::class, LoggerService::class);
        $this->app->bind(LoggerRepositoryContract::class, LoggerRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
