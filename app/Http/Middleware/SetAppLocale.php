<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetAppLocale
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = config('laravel_api.main_locale');
        if ($request->hasHeader('accept-language') &&
            in_array($request->header('accept-language'), config('laravel_api.available_locales',[]), true)) {
            $locale = $request->header('accept-language');
        }
        app()->setLocale($locale);

        return $next($request);
    }
}
