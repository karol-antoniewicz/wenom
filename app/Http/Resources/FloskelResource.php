<?php

namespace App\Http\Resources;

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
            'id' => $this->id,
            'text' => $this->text,
            'kuerzel' => $this->kuerzel,
        ];
    }
}
