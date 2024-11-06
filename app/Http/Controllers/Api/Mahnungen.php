<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leistung;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defining the Mahnungen controller
 */
class Mahnungen extends Controller
{
    public function __invoke(Leistung $leistung): JsonResponse
	{
        // Abort the operation if the 'Klasse' of the 'Schueler'
        // associated with the 'leistung' is not editable for 'Mahnungen'.
        abort_unless(
            $leistung->schueler->klasse->editable_mahnungen && $leistung->schueler->klasse->edit_overrideable,
            Response::HTTP_FORBIDDEN
        );

        // Updating the resource with an additional timestamp
		$leistung->update([
			'istGemahnt' => request()->istGemahnt,
			'tsIstGemahnt' => now()->format('Y-m-d H:i:s.u'),
		]);

        // Returning a JSON response with a 204 No Content status.
        return response()->json(Response::HTTP_NO_CONTENT);
    }
}
