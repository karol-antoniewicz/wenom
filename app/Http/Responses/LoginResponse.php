<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom implementation of the login response in a Laravel application.
 * This class implements the LoginResponseContract and provides a custom response after a user logs in.
 * It determines the response type based on the request's preference for a JSON response or a traditional web response.
 */
class LoginResponse implements LoginResponseContract
{
	/**
     * Create an HTTP response that represents the object. This method is invoked after a successful login.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function toResponse($request)
	{
        // Check if the request prefers a JSON response (commonly used for API or AJAX requests).
        return $request->wantsJson()
			? response()->json(['two_factor' => false])
			: redirect()->intended('admin/dashboard');
	}
}
