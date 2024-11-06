<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Settings\FilterSettings;
use App\Settings\GeneralSettings;
use App\Settings\MatrixSettings;
use App\Settings\SicherheitSettings;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defining the Settings controller
 */
class SettingsController extends Controller
{
    /**
     * Retrieves settings for a specified group.
     *
     * @param string $group
     * @return JsonResponse
     */
    public function index(string $group): JsonResponse
    {
        // Fetching settings for the specified group and returning them in a JSON response.
        return response()->json($this->getSetting($group));
    }

    /**
     * Updates a specific setting within a group.
     *
     * @param string $group
     * @return JsonResponse
     */
    public function update(string $group): JsonResponse
    {
        // Fetching the settings model for the specified group.
        $setting = $this->getSetting($group);
        // Updating the specified column in the settings model.
        $setting->{request()->column} = request()->value;
        // Saving the changes to the settings model.
        $setting->save();

        // Returning a JSON response with a 204 No Content status to indicate successful update.
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Performs a bulk update on settings within a group.
     *
     * @param string $group
     * @return JsonResponse
     */
    public function bulkUpdate(string $group): JsonResponse
    {
        // Fetching the settings model for the specified group.
        $setting = $this->getSetting($group);
        // Iterating through each setting from the request and updating the settings model.
        collect(request()->settings)->each(fn ($value, $key): string => $setting->$key = $value);
        // Saving the bulk changes to the settings model.
        $setting->save();

        // Returning a JSON response with a 204 No Content status to indicate successful bulk update.
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Retrieves the settings model based on the specified group.
     *
     * @param string $group
     * @return FilterSettings|MatrixSettings|SicherheitSettings|GeneralSettings
     */
    private function getSetting(string $group): FilterSettings | MatrixSettings | SicherheitSettings | GeneralSettings
    {
        // Determining and returning the appropriate settings model based on the group.
        return app(match ($group) {
            'filter' => FilterSettings::class,
            'matrix' => MatrixSettings::class,
            'sicherheit' => SicherheitSettings::class,
            //TODO
            //'2fa' => zfa::class,
            default => GeneralSettings::class,
        });
    }
}
