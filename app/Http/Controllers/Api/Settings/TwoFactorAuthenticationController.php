<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Services\EnvService;
use Illuminate\Http\{JsonResponse, Request};
use Symfony\Component\HttpFoundation\Response;

class TwoFactorAuthenticationController extends Controller
{
    /**
     * Get the setting value
     *
     * @return JsonResponse
     */
    public function get(): JsonResponse
	{
        return response()->json((bool) config('wenom.two_factor_authentication'), Response::HTTP_OK);
	}

    /**
     * Set the setting value
     *
     * @return JsonResponse
     */
    public function set(Request $request, EnvService $service): JsonResponse
    {
        $service->update('TWO_FACTOR_AUTHENTICATION', $request->enabled);

        return response()->json((bool) config('wenom.two_factor_authentication'), Response::HTTP_OK);
    }
}
