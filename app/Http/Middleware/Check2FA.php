<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class Check2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //dummy so far
        // if (enabled)) {
        //     echo "<script>console.log('is enabled' );</script>";
        // 	return $next($request);
		// }

		// return redirect()->route(route: 'mein_unterricht');

        //return $next($request);
        echo "<script>console.log('Reached redirection to twofactor login' );</script>";
        return redirect()->route(route: 'two-factor.login');
    }
}
