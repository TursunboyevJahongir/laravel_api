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
        if ($request->hasHeader('accept-language') &&
            in_array($request->header('accept-language'), AvailableLocalesEnum::toArray(), true)) {
            $locale = $request->header('accept-language');
        }
        app()->setLocale($locale);

        return $next($request);
    }
}
