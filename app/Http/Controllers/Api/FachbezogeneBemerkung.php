<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leistung;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defining the FachbezogeneBemerkung controller
 */
class FachbezogeneBemerkung extends Controller
{
    /**
     * A single-action controller method invoked when the controller is called.
     *
     * @param Leistung $leistung
     * @return JsonResponse
     */
    public function __invoke(Leistung $leistung): JsonResponse
	{
        // Checking if the 'klasse' related to the 'schueler' of the 'leistung' is editable.
        abort_unless(
            $leistung->schueler->klasse->editable_fb && $leistung->schueler->klasse->edit_overrideable,
            Response::HTTP_FORBIDDEN
        );

        // Updating the resource with an additional timestamp
		$leistung->update([
            'fachbezogeneBemerkungen' => request()->bemerkung,
            'tsFachbezogeneBemerkungen' => now()->format('Y-m-d H:i:s.u'),
        ]);

        // Returning a JSON response with a 204 No Content status.
		return response()->json(status: Response::HTTP_NO_CONTENT);
	}
}
