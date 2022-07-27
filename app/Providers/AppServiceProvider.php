<?php

namespace App\Providers;

use App\Contracts\{
    CategoryRepositoryContract,
    CategoryServiceContract,
    LoggerRepositoryContract,
    LoggerServiceContract,
    ProductRepositoryContract,
    ProductServiceContract,
    ResourceRepositoryContract,
    ResourceServiceContract,
    UserRepositoryContract,
    UserServiceContract
};
use App\Repositories\{
    CategoryRepository, LoggerRepository, ProductRepository, ResourceRepository, UserRepository
};
use App\Services\{
    CategoryService, LoggerService, ProductService, ResourceService, UserService
};
use App\Core\{
    Contracts\CoreRepositoryContract,
    Contracts\CoreServiceContract,
    Repositories\CoreRepository,
    Services\CoreService
};
use Illuminate\Database\Eloquent\Model;
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
        $this->app->bind(CategoryServiceContract::class, CategoryService::class);
        $this->app->bind(CategoryRepositoryContract::class, CategoryRepository::class);
        $this->app->bind(LoggerServiceContract::class, LoggerService::class);
        $this->app->bind(LoggerRepositoryContract::class, LoggerRepository::class);
        $this->app->bind(ProductServiceContract::class, ProductService::class);
        $this->app->bind(ProductRepositoryContract::class, ProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::preventLazyLoading(!app()->isProduction());
    }
}
