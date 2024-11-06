<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;

use App\Http\Requests\Settings\MailSendCredentialsRequest;
use App\Services\EnvService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Settings\FilterValidationRequest;

use Symfony\Component\HttpFoundation\Response;

/**
 * Defining the Env controller
 */
class EnvController extends Controller
{
    /**
     * Retrieves the email sending credentials from the configuration.
     *
     * @return JsonResponse
     */
    public function getMailSendCredentials(): JsonResponse
    {
        // Fetching the mail sending credentials from the configuration file and returning them in a JSON response.
        return response()->json(config('wenom.mail_send_credentials'), Response::HTTP_OK);
    }

    /**
     * Updates the email sending credentials.
     *
     * @param MailSendCredentialsRequest $request
     * @param EnvService $service
     * @return JsonResponse
     */
    public function updateMailSendCredentials(MailSendCredentialsRequest $request, EnvService $service): JsonResponse
    {
        // Attempting to bulk update the environment settings with validated request data.
        try {
            $service->bulkUpdate($request->validated());
        } catch (FileNotFoundException $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Returning a JSON response with a 204 No Content status, indicating successful update.
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Retrieves the general user filters from the configuration.
     *
     * @return JsonResponse
     */
    public function getFilters(): JsonResponse
    {
        // Fetching the general user filters from the application's configuration file and returning them in a JSON response.
        return response()->json(config('wenom.filters'));
    }

    /**
     * Sets the user filters based on the provided request data.
     *
     * @param FilterValidationRequest $request
     * @param EnvService $service
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function setFilters(FilterValidationRequest $request, EnvService $service): JsonResponse
    {
        // Iterating through each filter item and updating the environment settings accordingly.
        collect($request->all())->each(fn (array $item, string $key) =>
            collect($item)->each(fn ($value, string $itemKey) =>
                $service->update(
                    sprintf('%s_%s', strtoupper($key), strtoupper($itemKey)), $value
                )
            )
        );

        // Returning a JSON response with a 204 No Content status, indicating successful update.
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
