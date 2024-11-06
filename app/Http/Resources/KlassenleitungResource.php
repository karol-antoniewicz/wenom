<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `KlassenleitungResource` class is a JSON resource for formatting and presenting 'Klassenleitung' data.
 *
 * @package App\Http\Resources\Export
 */
class KlassenleitungResource extends JsonResource
{
    /**
     * Transform the data into a JSON array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
			'id' => $this->id,
			'nachname' => $this->nachname,
			'vorname' => $this->vorname,
			'name' => "{$this->nachname}, {$this->vorname}",
			'geschlecht' => $this->geschlecht,
			'klasse' => $this->klasse->kuerzelAnzeige,
			'matrix' => new MatrixResource($this->klasse),
			'ASV' => $this->bemerkung?->ASV,
			'AUE' => $this->bemerkung?->AUE,
			'ZB' => $this->bemerkung?->ZB,
			'gfs' => $this->lernabschnitt?->fehlstundenGesamt,
			'gfsu' => $this->lernabschnitt?->fehlstundenGesamtUnentschuldigt,
		];
    }
}
