<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `FloskelResource` class is a JSON resource for formatting and presenting 'Floskel' data.
 *
 * @package App\Http\Resources\Export
 */
class FloskelResource extends JsonResource
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
            'kuerzel' => $this->kuerzel,
            'text' => $this->text,
            'fachID' => $this->fach?->ext_id,
            'niveau' => $this->niveau,
            'jahrgangID' => $this->jahrgang?->ext_id,
        ];
    }
}
