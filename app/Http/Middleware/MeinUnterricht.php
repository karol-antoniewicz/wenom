<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Defining the MeinUnterricht middleware
 */
class MeinUnterricht
{
    /**
     * Handle an incoming request.
     * This middleware checks if the authenticated user has any Lerngruppen.
     * If the user has Lerngruppen, the request is allowed to proceed. Otherwise, the user is redirected.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        // Check if the authenticated user has any Lerngruppen.
        $hasLerngruppen = auth()->user()->lerngruppen()->count();

        // If the user has Lerngruppen, continue processing the request.
        if ($hasLerngruppen) {
			return $next($request);
		}

        // If the user does not have any Lerngruppen, redirect them to the Leistungsdatenuebersicht route.
        return redirect(route('leistungsdatenuebersicht'));
    }
}
