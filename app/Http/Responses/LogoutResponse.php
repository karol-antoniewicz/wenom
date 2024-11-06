<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Symfony\Component\HttpFoundation\Response as Status;

/**
 * This class implements the LogoutResponseContract from Laravel Fortify and provides a custom response after a user logs out.
 * It determines the response type based on the request's preference for a JSON response or a traditional web response.
 */
class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object. This method is invoked after a successful logout.
     *
     * @param  Request  $request
     * @return Response
     */
	public function toResponse($request)
    {
        // Check if the request prefers a JSON response (commonly used for API or AJAX requests).
        return $request->wantsJson()
			? new JsonResponse('', Status::HTTP_NO_CONTENT)
			: redirect('/login');
	}
}
