<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `TeilleistungResource` class is a JSON resource for formatting and presenting 'Teilleistung' data.
 *
 * @package App\Http\Resources\Export
 */
class TeilleistungResource extends JsonResource
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
            'artID' => $this->teilleistungsart?->id,
            'tsArtID' => $this->tsArtID,
            'datum' => $this->datum,
            'tsDatum' => $this->tsDatum,
            'bemerkung' => $this->bemerkung,
            'tsBemerkung' => $this->tsBemerkung,
            'note' => $this->note?->kuerzel,
            'tsNote' => $this->tsNote,
        ];
    }
}
