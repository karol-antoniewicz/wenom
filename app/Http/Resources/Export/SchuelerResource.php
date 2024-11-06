<?php

namespace App\Http\Resources\Export;

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
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'jahrgangID' => $this->jahrgang->id,
            'klasseID' => $this->klasse->id,
            'nachname' => $this->nachname,
            'vorname' => $this->vorname,
            'geschlecht' => $this->geschlecht,
            'bilingualeSprache' => $this->bilingualeSprache?->kuerzel,
            'istZieldifferent' => $this->istZieldifferent,
            'istDaZFoerderung' => $this->istDaZFoerderung,
            'sprachenfolge' => SprachenfolgeResource::collection($this->sprachenfolge),
            'lernabschnitt' => new LernabschnittResource($this->lernabschnitt),
            'leistungsdaten' => LeistungsdatenResource::collection($this->leistungen),
            'bemerkungen' => new BemerkungResource($this->bemerkung),
            'zp10' => Zp10Resource::make($this->zp10),
            'bkabschluss' => BkAbschlussResource::make($this->bkabschluss),
        ];
    }
}
