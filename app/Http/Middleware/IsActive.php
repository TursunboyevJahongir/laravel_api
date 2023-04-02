<?php

namespace App\Http\Middleware;

use App\Core\Traits\Responsible;
use Closure;
use Illuminate\Http\Request;

class IsActive
{
    use Responsible;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth()->check() && !auth()->user()->is_active) {
            return $this->responseWith(code: ResponseCode::HTTP_FORBIDDEN, message: __("messages.account_not_active"));
        }

        return $next($request);
    }
}
