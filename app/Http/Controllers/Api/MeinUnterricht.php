<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeinUnterricht\LeistungResource;
use App\Models\{Leistung, Note};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Defining the MeinUnterricht controller
 */
class MeinUnterricht extends Controller
{
    /**
     * Single-action method to fetch and return a collection of Leistung records.
     *
     * @return AnonymousResourceCollection
     */
    public function __invoke(): AnonymousResourceCollection
    {
        // Define the relationships to be eagerly loaded.
        $eagerLoadedColumns = [
			'schueler' => ['klasse', 'jahrgang'],
			'lerngruppe' => ['lehrer', 'fach'],
			'note', 'quartalNote',
		];

        // Build the query for Leistung, including eager loading and conditional logic.
        $leistungen = Leistung::query()
            // Eager load the defined relationships for performance optimization.
            ->with($eagerLoadedColumns)
            // Conditional logic to modify the query if the authenticated user is a Lehrer.
            ->when(
				auth()->user()->isLehrer(),
                // If the user is a Lehrer, limit the query to Leistung records linked to the Lehrer's Lerngruppen.
                fn (Builder $query): Builder => $query->whereHas('lerngruppe',
					fn (Builder $query): Builder => $query->whereIn('id',
                        auth()->user()->lerngruppen->pluck(value: 'id')->toArray()
					)
				)
			)
            // Execute the query and retrieve the results.
            ->get()
            // Sort the results based on specific columns.
            ->sortBy([
                'schueler.klasse.kuerzel', 'schueler.nachname', 'lerngruppe.fach.kuerzelAnzeige',
            ]);
        
        // Sort Leistungen per klasseKuerzel (eg. 5a, 6b...)
        // usort($leistungen, fn (array $a, array $b): bool => $a['klasse'] >  $b['klasse']);

        //Get all notes present in the noten DB table
        $allNotes = Note::query()
            ->orderBy('sortierung')
            ->pluck('kuerzel')
            ->toArray();

        // Return the collection of Leistung resources, with additional data.
		return LeistungResource::collection($leistungen)
            ->additional([
                'toggles' => auth()->user()->filters('meinunterricht'),
                'allNotes' => $allNotes,
            ]);
	}
}
