<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `KlasseResource` class is a JSON resource for formatting and presenting 'Klasse' data.
 *
 * @package App\Http\Resources\Export
 */
class KlasseResource extends JsonResource
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
            'kuerzel' => $this->kuerzel,
            'kuerzelAnzeige' => $this->kuerzelAnzeige,
            'idJahrgang' => $this->idJahrgang,
            'sortierung' => $this->sortierung,
            'klassenlehrer' => $this->klassenlehrer->pluck('id'),
        ];
    }
}
