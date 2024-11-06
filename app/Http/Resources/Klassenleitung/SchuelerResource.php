<?php

namespace App\Http\Resources\Klassenleitung;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `SchuelerResource` class is a JSON resource for formatting and presenting 'Schueler' data.
 *
 * @package App\Http\Resources\Export
 */
class SchuelerResource extends JsonResource
{
    /**
     * Transform the data into a JSON array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nachname' => $this->nachname,
            'vorname' => $this->vorname,
            'name' => "{$this->nachname}, {$this->vorname}",
            'geschlecht' => $this->geschlecht,
            'klasse' => $this->klasse->kuerzelAnzeige,
            'ASV' => $this->bemerkung?->ASV,
            'AUE' => $this->bemerkung?->AUE,
            'ZB' => $this->bemerkung?->ZB,
            'gfs' => $this->lernabschnitt?->fehlstundenGesamt,
            'gfsu' => $this->lernabschnitt?->fehlstundenGesamtUnentschuldigt,
            'editable' => new MatrixResource($this->klasse)
        ];
    }
}
