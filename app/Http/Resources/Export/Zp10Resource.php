<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `Zp10Resource` class is a JSON resource for formatting and presenting 'Zp10' data.
 *
 * @package App\Http\Resources\Export
 */
class Zp10Resource extends JsonResource
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
            'fachID' => $this->fach->id,
            'vornote' => $this->vornote?->kuerzel,
            'noteSchriftlichePruefung' => $this->noteSchriftlichePruefung?->kuerzel,
            'muendlichePruefung' => $this->muendlichePruefung,
            'muendlichePruefungFreiwillig' => $this->muendlichePruefungFreiwillig,
            'noteMuendlichePruefung' => $this->noteMuendlichePruefung?->kuerzel,
            'abschlussnote' => $this->abschlussnote?->kuerzel,
        ];
    }
}
