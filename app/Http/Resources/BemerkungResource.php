<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `BemerkungResource` class is a JSON resource for formatting and presenting 'Bemerkung' data.
 *
 * @package App\Http\Resources\Export
 */
class BemerkungResource extends JsonResource
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
            'asv' => (bool) $this->asv,
            'aue' => (bool) $this->aue,
            'zb' => (bool) $this->zb,
        ];
    }
}
