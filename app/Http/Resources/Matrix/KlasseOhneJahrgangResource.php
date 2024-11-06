<?php

namespace App\Http\Resources\Matrix;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `KlasseOhneJahrgangResource` class is a JSON resource for formatting and presenting 'KlasseOhneJahrgang' data.
 *
 * @package App\Http\Resources\Export
 */
class KlasseOhneJahrgangResource extends JsonResource
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
			'id' => 123,
			'kuerzel' => 345,
			'stufe' => 678,
			'klassen' => KlasseResource::collection($this->klassen)
		];
    }
}