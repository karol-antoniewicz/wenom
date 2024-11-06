<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fehlstunden\FsRequest;
use App\Http\Requests\Fehlstunden\GfsRequest;
use App\Http\Requests\Fehlstunden\GfsuRequest;
use App\Http\Requests\Fehlstunden\FsuRequest;
use App\Models\Leistung;
use App\Models\Lernabschnitt;
use App\Models\Schueler;
use App\Settings\MatrixSettings;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defining the Fehlstunden controller
 */
class Fehlstunden extends Controller
{
    /**
     * Handles updating the Fach-related absences.
     *
     * @param FsRequest $request
     * @param Leistung $leistung
     * @param MatrixSettings $settings
     * @return Response
     * @throws AuthorizationException
     */
	public function fs(FsRequest $request, Leistung $leistung, MatrixSettings $settings): Response
	{
        // Authorizes the user to update the Leistung record.
        $this->authorize('update', [$leistung, $settings]);

        // Updating the resource with an additional timestamp
        $leistung->update([
			'fehlstundenFach' => $request->get('value'),
			'tsFehlstundenFach' => now()->format('Y-m-d H:i:s.u'),
		]);

        // Returning a JSON response with a 204 No Content status.
		return response(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Handles updating the unexcused Fach-related absences.
     *
     * @param FsuRequest $request
     * @param Leistung $leistung
     * @param MatrixSettings $settings
     * @return Response
     * @throws AuthorizationException
     */
	public function fsu(FsuRequest $request, Leistung $leistung, MatrixSettings $settings): Response
	{
        // Authorizes the user to update the Leistung record.
		$this->authorize('update', [$leistung, $settings]);

        // Updating the resource with an additional timestamp
        $leistung->update([
			'fehlstundenUnentschuldigtFach' => $request->get('value'),
			'tsFehlstundenUnentschuldigtFach' => now()->format('Y-m-d H:i:s.u'),
		]);

        // Returning a JSON response with a 204 No Content status.
        return response(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Handles updating the Gesamt-related absences.
     *
     * @param GfsRequest $request
     * @param Schueler $schueler
     * @return Response
     * @throws AuthorizationException
     */
	public function gfs(GfsRequest $request, Schueler $schueler): Response
	{
        // Authorizes the user to update the Leistung record.
        $this->authorize('update', $schueler);

        // Updating the resource with an additional timestamp
        $schueler->lernabschnitt->update([
			'fehlstundenGesamt' => $request->get('value'),
			'tsFehlstundenGesamt' => now()->format('Y-m-d H:i:s.u'),
		]);

        // Returning a JSON response with a 204 No Content status.
        return response(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Handles updating the unexcused Gesamt-related absences (total unexcused absences).
     *
     * @param GfsuRequest $request
     * @param Schueler $schueler
     * @return Response
     * @throws AuthorizationException
     */
	public function gfsu(GfsuRequest $request, Schueler $schueler): Response
	{
        // Authorizes the user to update the Leistung record.
        $this->authorize('update', $schueler);

        // Updating the resource with an additional timestamp
		Lernabschnitt::whereBelongsTo($schueler)->first()->update([
			'fehlstundenGesamtUnentschuldigt' => $request->get('value'),
			'tsFehlstundenGesamtUnentschuldigt' => now()->format('Y-m-d H:i:s.u'),
		]);

        // Returning a JSON response with a 204 No Content status.
        return response(status: Response::HTTP_NO_CONTENT);
    }
}
