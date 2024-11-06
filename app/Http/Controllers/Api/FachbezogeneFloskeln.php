<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FachBezogeneFloskelResource;
use App\Models\Fach;
use App\Models\Floskel;
use App\Models\Floskelgruppe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

/**
 * Defining the FachbezogeneFloskeln controller
 */
class FachbezogeneFloskeln extends Controller
{
    /**
     * A single-action controller method invoked when the controller is called.
     *
     * @param Fach $fach
     * @return JsonResponse
     */
    public function __invoke(Fach $fach): JsonResponse
	{
        // Attempting to find the Floskelgruppe with the specified 'kuerzel'.
        try {
			$floskelgruppe = Floskelgruppe::query()
				->where('kuerzel', '=', 'FACH')
				->firstOrFail();
		} catch (ModelNotFoundException $e) {
			return response()->json(FachBezogeneFloskelResource::collection([]));
		}

        // Fetching Floskeln that belong to both the Floskelgruppe and the given Fach.
		$floskeln = Floskel::query()
			->whereBelongsTo( $floskelgruppe)
			->whereBelongsTo($fach)
			->with('jahrgang')
			->get();

        // Mapping function for 'niveau' of Floskeln.
        $mapNiveau = fn (Floskel $floskel): array => [
			'index' => $floskel->niveau,
			'label' => $floskel->niveau
		];

        // Mapping function for 'jahrgaenge' of Floskeln.
		$mapJahrgaenge = fn (Floskel $floskel): array => [
			'index' => $floskel->jahrgang?->kuerzel,
			'label' => $floskel->jahrgang?->kuerzel,
		];

        // Returning the fetched Floskeln, and their unique 'niveau' and 'jahrgaenge' in the JSON response.
		return response()->json([
			'data' => FachBezogeneFloskelResource::collection($floskeln),
			'niveau' => $floskeln->unique('niveau')->map($mapNiveau)->values(),
			'jahrgaenge' => $floskeln->unique('jahrgang_id')->map($mapJahrgaenge)->values(),
		]);
	}
}
