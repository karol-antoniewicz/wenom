<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FloskelResource;
use App\Models\Floskel;
use App\Models\Floskelgruppe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Defining the Floskeln controller
 */
class Floskeln extends Controller
{
    /**
     * Single-action controller method, invoked when the controller is called.
     *
     * @param string $floskelgruppe
     * @return Collection|JsonResponse|array
     */
    public function __invoke(string $floskelgruppe): Collection|JsonResponse|array
	{
        // Attempting to find a Floskelgruppe by its 'kuerzel'.
        try {
			$gruppe = Floskelgruppe::query()
				->where('kuerzel', '=', $floskelgruppe)
				->firstOrFail();
		} catch (NotFoundHttpException $e) {
			return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
		}

        // If the Floskelgruppe is found, return a collection of Floskeln associated with that group as a resource,
        return response()->json(FloskelResource::collection(
			Floskel::query()->whereBelongsTo($gruppe)->get()
		));
	}
}
