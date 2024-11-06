<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `LerngruppeResource` class is a JSON resource for formatting and presenting 'Lerngruppe' data.
 *
 * @package App\Http\Resources\Export
 */
class LerngruppeResource extends JsonResource
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
            'kID' => $this->kID,
            'fachID' => $this->fach?->id,
            'kursartID' => $this->kursartID,
            'bezeichnung' => $this->bilingualeSprache,
            'kursartKuerzel' => $this->kuerzel,
            'bilingualeSprache' => $this->bilingualeSprache,
            'lehrerID' => $this->lehrer->pluck('ext_id'),
            'wochenstunden' => $this->wochenstunden,
        ];
    }
}
