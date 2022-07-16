<?php

namespace App\Providers;

use App\Http\Middleware\IsActive;
use App\Http\Middleware\SetAppLocale;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureRateLimiting();
        $this->aliasMiddleware('setAppLocale', SetAppLocale::class);
        $this->aliasMiddleware('isActive', IsActive::class);

        $this->routes(function () {
            Route::prefix('api/v1')
                ->middleware(['api', 'auth:api', 'setAppLocale', 'bindings', 'isActive'])
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ? : $request->ip());
        });
    }
}
