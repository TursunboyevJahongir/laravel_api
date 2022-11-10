<?php

namespace App\Http\Middleware;

use App\Core\Traits\Responsable;
use Closure;
use Illuminate\Http\Request;

class IsActive
{
    use Responsable;

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
        if (auth()->check() && !auth()->user()->is_active) {
            return $this->responseWith(code: 403, message: __("messages.not_access"));
        }

        return $next($request);
    }
}
