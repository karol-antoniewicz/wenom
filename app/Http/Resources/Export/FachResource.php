<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `FachResource` class is a JSON resource for formatting and presenting 'Fach' data.
 *
 * @package App\Http\Resources\Export
 */
class FachResource extends JsonResource
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
            'sortierung' => $this->sortierung,
            'istFremdsprache' => (bool) $this->istFremdsprache,
        ];
    }
}
