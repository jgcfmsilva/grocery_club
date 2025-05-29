<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotEmployee
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && method_exists(authUser(), 'isEmployee') && authUser()->isEmployee()) {
            abort(403, 'Employees are not allowed to access this resource.');
        }
        return $next($request);
    }
}
