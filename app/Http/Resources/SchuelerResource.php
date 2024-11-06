<?php

namespace App\Http\Resources;

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
            'klasseID' => $this->klasse->id,
            'jahrgangID' => $this->jahrgang->id,
            'name' => implode(separator: ', ', array: [$this->nachname, $this->vorname]),
            'nachname' => $this->nachname,
            'vorname' => $this->vorname,
            'geschlecht' => $this->geschlecht,
            'bilingualeSprache' => $this->bilingualeSprache ?? 'null',
            'istZieldifferent' => $this->istZieldifferent,
            'istDaZFoerderung' => $this->istDaZFoerderung,
            'jahrgang_id' => $this->jahrgang->id,
            'bemerkungen' => new BemerkungResource(resource: $this->bemerkung),
        ];
    }
}
