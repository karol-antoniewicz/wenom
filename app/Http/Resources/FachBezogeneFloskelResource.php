<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `FachBezogeneFloskelResource` class is a JSON resource for formatting and presenting 'FachBezogeneFloskel' data.
 *
 * @package App\Http\Resources\Export
 */
class FachBezogeneFloskelResource extends JsonResource
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
			'niveau' => $this->niveau,
			'jahrgang' => $this->jahrgang?->kuerzel,
		];
    }
}
