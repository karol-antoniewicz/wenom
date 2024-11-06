<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Defining the RedirectIfAdministrator middleware
 */
class RedirectIfAdministrator
{
    /**
     * Handle an incoming request.
     * This middleware checks if the authenticated user is an administrator.
     * If so, the request is allowed to proceed. Otherwise, the user is redirected.
     *
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Check if the user is authenticated and is an administrator.
        if (auth()->check() && auth()->user()->isAdministrator()) {
            // If the user is an administrator, continue processing the request.
            return $next($request);
		}

        // If the user is not an administrator, redirect them to the 'mein_unterricht' route.
		return redirect()->route('mein_unterricht');
    }
}
