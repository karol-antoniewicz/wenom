<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken extends Middleware
{
    public function handle($request, Closure $next): Response
    {
        if ($request->route()->named('logout')) {
            if (auth()->guest() || auth()->guard()->viaRemember()) {
                $this->except[] = route('logout');
            }
        }

        return parent::handle($request, $next);
    }
}
