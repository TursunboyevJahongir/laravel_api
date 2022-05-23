<?php

namespace App\Providers;

use App\Contracts\TestakssddfServiceContract;
use App\Services\TestakssddfService;
use App\Contracts\TestakssddfRepositoryContract;
use App\Repositories\TestakssddfRepository;
use App\Contracts\TestakssdServiceContract;
use App\Services\TestakssdService;
use App\Contracts\TestakssdRepositoryContract;
use App\Repositories\TestakssdRepository;
use App\Contracts\TestaksServiceContract;
use App\Services\TestaksService;
use App\Contracts\TestaksRepositoryContract;
use App\Repositories\TestaksRepository;
use App\Contracts\TestServiceContract;
use App\Services\TestService;
use App\Contracts\TestRepositoryContract;
use App\Repositories\TestRepository;
use App\Contracts\ResourceRepositoryContract;
use App\Contracts\ResourceServiceContract;
use App\Contracts\UserRepositoryContract;
use App\Contracts\UserServiceContract;
use App\Core\Contracts\CoreRepositoryContract;
use App\Core\Contracts\CoreServiceContract;
use App\Core\Repositories\CoreRepository;
use App\Core\Services\CoreService;
use App\Repositories\ResourceRepository;
use App\Repositories\UserRepository;
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

$this->app->bind(TestakssddfServiceContract::class, TestakssddfService::class);
$this->app->bind(TestakssddfRepositoryContract::class, TestakssddfRepository::class);

$this->app->bind(TestakssdServiceContract::class, TestakssdService::class);
$this->app->bind(TestakssdRepositoryContract::class, TestakssdRepository::class);

$this->app->bind(TestaksServiceContract::class, TestaksService::class);
$this->app->bind(TestaksRepositoryContract::class, TestaksRepository::class);

$this->app->bind(TestServiceContract::class, TestService::class);
$this->app->bind(TestRepositoryContract::class, TestRepository::class);

        $this->app->bind(UserServiceContract::class, UserService::class);
        $this->app->bind(ResourceServiceContract::class, ResourceService::class);
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(ResourceRepositoryContract::class, ResourceRepository::class);
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
