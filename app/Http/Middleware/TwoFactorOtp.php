<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorOtp
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if OTP is required for the route

        /* @var User $user|null */
        $user = auth()->user();

        if ($user?->mustVerifyOtp() ?? false) {
            return redirect()->route('otp');
        }

        return $next($request);
    }
}
