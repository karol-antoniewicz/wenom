<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `SprachenfolgeResource` class is a JSON resource for formatting and presenting 'Sprachenfolge' data.
 *
 * @package App\Http\Resources\Export
 */
class SprachenfolgeResource extends JsonResource
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
            'sprache' => $this->fach?->kuerzel,
            'fachID' => $this->fach?->id,
            'fachKuerzel' => $this->fach?->kuerzel,
            'reihenfolge' => $this->reihenfolge,
            'belegungVonJahrgang' => $this->belegungVonJahrgang,
            'belegungVonAbschnitt' => $this->belegungVonAbschnitt,
            'belegungBisJahrgang' => $this->belegungBisJahrgang,
            'belegungBisAbschnitt' => $this->belegungBisAbschnitt,
            'referenzniveau' => $this->referenzniveau,
            'belegungSekI' => $this->belegungSekI,
        ];
    }
}
