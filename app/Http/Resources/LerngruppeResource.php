<?php

namespace App\Http\Resources;

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
            'kID' => (int) $this->kID,
            'fachID' => $this->fachID,
            'kursartID' => $this->kursartID,
            'bezeichnung' => $this->bezeichnung,
            'kursartKuerzel' => $this->kursartKuerzel,
            'bilingualeSprache' => $this->bilingualeSprache,
            'lehrerID' => $this->lehrer->pluck('id'),
            'wochenstunden' => $this->wochenstunden,
        ];
    }
}
