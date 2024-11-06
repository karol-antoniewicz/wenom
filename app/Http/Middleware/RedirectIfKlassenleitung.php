<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Defining the RedirectIfKlassenleitung middleware
 */
class RedirectIfKlassenleitung
{
    /**
     * Handle an incoming request.
     * This middleware checks if the authenticated user belongs to any classes (klassen) or if they are an administrator.
     * If either condition is true, the request is allowed to proceed. Otherwise, the user is redirected to a specific route.
     *
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|JsonResponse|Response
     */
    public function handle(Request $request, Closure $next): RedirectResponse|JsonResponse|Response
    {
        // Check if the authenticated user is associated with any classes or is an administrator.
        if (auth()->user()->klassen->count() > 0 || auth()->user()->isAdministrator()) {
            // If the user is part of any class or is an administrator, continue processing the request.
            return $next($request);
		}

        // If the user does not meet the above conditions, redirect them to the 'mein_unterricht' route.
		return redirect()->route('mein_unterricht');
    }
}
