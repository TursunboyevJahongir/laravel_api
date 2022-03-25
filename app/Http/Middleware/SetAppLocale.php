<?php

namespace App\Http\Middleware;

use App\Enums\AvailableLocalesEnum;
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
        $locale = config('app.main_locale');
        $sentLocale = $request->header('accept-language');
        if ($request->hasHeader('accept-language') && in_array($sentLocale, AvailableLocalesEnum::toArray(), true)) {
            $locale = $sentLocale;
        }
        app()->setLocale($locale);

        return $next($request);
    }
}
