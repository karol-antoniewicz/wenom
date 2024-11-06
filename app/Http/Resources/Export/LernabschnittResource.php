<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `LernabschnittResource` class is a JSON resource for formatting and presenting 'Lernabschnitt' data.
 *
 * @package App\Http\Resources\Export
 */
class LernabschnittResource extends JsonResource
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
			'fehlstundenGesamt' => $this->fehlstundenGesamt,
			'tsFehlstundenGesamt' => $this->tsFehlstundenGesamt,
			'fehlstundenGesamtUnentschuldigt' => $this->fehlstundenGesamtUnentschuldigt,
			'tsFehlstundenGesamtUnentschuldigt' => $this->tsFehlstundenGesamtUnentschuldigt,
            'pruefungsordnung' => $this->pruefungsordnung,
            'lernbereich1note' => $this->lernbereich1Note?->kuerzel,
            'lernbereich2note' => $this->lernbereich2Note?->kuerzel,
            'foerderschwerpunkt1' => $this->foerderschwerpunkt1Relation?->kuerzel,
            'foerderschwerpunkt2' => $this->foerderschwerpunkt2Relation?->kuerzel,
        ];
    }
}
