<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `FoerderschwerpunkteResource` class is a JSON resource for formatting and presenting 'Foerderschwerpunkte' data.
 *
 * @package App\Http\Resources\Export
 */
class FoerderschwerpunkteResource extends JsonResource
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
            'id' => $this->sortierung,
            'kuerzel' => $this->kuerzel,
            'beschreibung' => $this->beschreibung,
        ];
    }
}
