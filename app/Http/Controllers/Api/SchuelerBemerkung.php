<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchuelerBemerkungenRequest;
use App\Models\Bemerkung;
use App\Models\Schueler;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defining the FachbezogeneBemerkung controller
 */
class SchuelerBemerkung extends Controller
{
    /**
     * @param SchuelerBemerkungenRequest $request
     * @param Schueler $schueler
     * @return Response
     */
    public function __invoke(SchuelerBemerkungenRequest $request, Schueler $schueler): Response
    {
        // Dynamically creating a key to check if the Bemerkung type is editable for the Schueler class.
        $key = sprintf('editable_%s', strtolower($request->key));

        // Check if the specified Bemerkung type is editable for this Schueler class.
        abort_unless($schueler->klasse->$key, Response::HTTP_FORBIDDEN);

        // Update an existing Bemerkung or create a new one with the specified key and value,
        // and update the corresponding timestamp.
		Bemerkung::updateOrCreate(
			['schueler_id' => $schueler->id],
			[
				$request->key => $request->value,
				"ts{$request->key}" => now()->format(format: 'Y-m-d H:i:s.u'),
			]
		);

        // Returning a JSON response with a 204 No Content status.
		return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
