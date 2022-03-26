<?php

namespace App\Providers;

use App\Contracts\UserRepositoryContract;
use App\Contracts\UserServiceContract;
use App\Core\Contracts\CoreRepositoryContract;
use App\Core\Contracts\CoreServiceContract;
use App\Core\Repositories\CoreRepository;
use App\Core\Services\CoreService;
use App\Repositories\UserRepository;
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
        $this->app->bind(CoreServiceContract::class, CoreService::class);
        $this->app->bind(CoreRepositoryContract::class, CoreRepository::class);
        $this->app->bind(UserServiceContract::class, UserService::class);
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
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
