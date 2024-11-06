<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `TeilleistungsartResource` class is a JSON resource for formatting and presenting 'Teilleistungsart' data.
 *
 * @package App\Http\Resources\Export
 */
class TeilleistungsartResource extends JsonResource
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
            'bezeichnung' => $this->bezeichnung,
            'sortierung' => $this->sortierung,
            'gewichtung' => $this->gewichtung,
        ];
    }
}
