<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `FloskelgruppeResource` class is a JSON resource for formatting and presenting 'Floskelgruppe' data.
 *
 * @package App\Http\Resources\Export
 */
class FloskelgruppeResource extends JsonResource
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
            'bezeichnung' => $this->bezeichnung,
            'hauptgruppe' => $this->hauptgruppe,
            'floskeln' => FloskelResource::collection($this->floskeln),
        ];
    }
}
