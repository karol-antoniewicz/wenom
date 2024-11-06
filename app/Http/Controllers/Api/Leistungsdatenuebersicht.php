<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeinUnterricht\LeistungResource;
use App\Models\{Leistung, Note};
use App\Settings\MatrixSettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Defining the Leistungsdatenuebersicht controller
 */
class Leistungsdatenuebersicht extends Controller
{
    /**
     * Fetches all Leistung Models records and returns as a formatted resource.
     * Lehrer-users get only records where the Klasse Model intersects with their Klasse relationship.
     * Admin users get all records returned.
     *
     * @param MatrixSettings $matrix
     * @return AnonymousResourceCollection
     */
	public function __invoke(MatrixSettings $matrix): AnonymousResourceCollection
	{
        // Define the relationships to be eager loaded to improve performance.
        $eagerLoadedColumns = [
			'schueler' => ['klasse', 'jahrgang'],
			'lerngruppe' => ['lehrer', 'fach'],
			'note', 'quartalNote',
		];

        // Define the columns for sorting the results.
		$sortByColumns = [
			'schueler.klasse.kuerzel',
			'schueler.nachname',
			'lerngruppe.fach.kuerzelAnzeige',
		];

        // Build the query for Leistung, including eager loading and conditional logic.
        $leistungen = Leistung::query()
			->with($eagerLoadedColumns)
            // Apply additional conditions if the authenticated user is a Lehrer.
            ->when(
				auth()->user()->isLehrer(),
				fn (Builder $query): Builder => $query->whereHas(
					'schueler',
					fn (Builder $query): Builder => $query->whereIn(
						'klasse_id',
						auth()->user()->klassen()->select('id')
					)
				)
			)
            // Execute the query and retrieve the results.
            ->get()
            // Sort the results by the specified columns.
            ->sortBy($sortByColumns);

        //Get all notes present in noten DB table
        $allNotes = Note::query()
            ->orderBy('sortierung')
            ->pluck('kuerzel')
            ->toArray();

        // Return the collection of Leistung resources.
        return LeistungResource::collection($leistungen)
            ->additional([
                'toggles' => auth()->user()->filters('leistungsdatenuebersicht'),
                'lehrerCanOverrideFachlehrer' => $matrix->lehrer_can_override_fachlehrer,
                'allNotes' => $allNotes,
            ]);
    }
}
