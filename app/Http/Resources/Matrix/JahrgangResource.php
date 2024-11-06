<?php

namespace App\Http\Resources\Matrix;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `JahrgangResource` class is a JSON resource for formatting and presenting 'Jahrgang' data.
 *
 * @package App\Http\Resources\Export
 */
class JahrgangResource extends JsonResource
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
			'stufe' => $this->stufe,
			'klassen' => KlasseResource::collection($this->klassen)
		];
    }
}
