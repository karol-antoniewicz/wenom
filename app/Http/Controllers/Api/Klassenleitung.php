<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Klassenleitung\SchuelerResource;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Defining the Klassenleitung controller
 */
class Klassenleitung extends Controller
{
    /**
     * Single-action controller method to retrieve and return a collection of student resources.
     *
     * @return AnonymousResourceCollection
     */
    public function __invoke(): AnonymousResourceCollection
	{
        // Querying the Schueler model with eager loaded relations
        $schueler = Schueler::query()
			->with(['klasse', 'leistungen', 'bemerkung', 'lernabschnitt'])
            // Conditional logic to modify the query if the authenticated user is a Lehrer.
            ->when(
				auth()->user()->isLehrer(),
                // If the user is a Lehrer, limit the query to Schueler in classes taught by this Lehrer.
                fn (Builder $query): Builder => $query->whereIn(
					'klasse_id',
					auth()->user()->klassen()->pluck('id')
				)
			)
			->get()
            // Sorting the collection by Kuerzel and Schueler last name.
            ->sortBy(fn (Schueler $schueler): array => [
				$schueler->klasse->kuerzel,
				$schueler->nachname,
			]);

        // Returning the collection of SchuelerResource.
        return SchuelerResource::collection($schueler);
	}
}
