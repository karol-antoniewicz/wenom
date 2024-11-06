<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\Matrix\JahrgangResource;
use App\Http\Resources\Matrix\KlasseResource;
use App\Models\Jahrgang;
use App\Models\Klasse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defining the Matrix controller
 */
class MatrixController extends Controller
{
    /**
     *  * Retrieves and groups Jahrgang resources, and retrieves Klasse resources.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Retrieving Jahrgang resources, ordering them, and then grouping by Stufe.
        $jahrgaenge = JahrgangResource::collection(Jahrgang::orderedWithKlassenOrdered())->collection->groupBy('stufe');

        // Retrieving Klasse resources that do not belong to a Jahrgang, ordered.
        $klassen = KlasseResource::collection(Klasse::notBelongingToJahrgangOrdered());

        // Returning the Jahrgang and Klasse data in a JSON response.
        return response()->json(['jahrgaenge' => $jahrgaenge, 'klassen' => $klassen], Response::HTTP_OK);
    }

    /**
     * Updates multiple Klasse records based on the request data.
     *
     * @return JsonResponse
     */
    public function update(): JsonResponse
    {
        // Specifying the fields that can be updated.
        $only = [
            'edit_overrideable', 'editable_teilnoten', 'editable_noten', 'editable_mahnungen', 'editable_fehlstunden',
            'toggleable_fehlstunden', 'editable_fb', 'editable_asv', 'editable_aue', 'editable_zb',
        ];

        // Iterating over each Klasse from the request, finding it by ID, and updating specified fields.
        collect(request()->klassen)->each(fn (array $klasse) =>
            Klasse::find($klasse['id'])->update(Arr::only($klasse, $only))
        );

        // Returning a JSON response with a 204 No Content status, indicating successful update.
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

}
