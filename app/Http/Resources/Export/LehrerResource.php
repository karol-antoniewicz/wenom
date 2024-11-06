<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `LehrerResource` class is a JSON resource for formatting and presenting 'Lehrer' data.
 *
 * @package App\Http\Resources\Export
 */
class LehrerResource extends JsonResource
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
            'id' => $this->ext_id,
            'kuerzel' => $this->kuerzel,
            'nachname' => $this->nachname,
            'vorname' => $this->vorname,
            'geschlecht' => $this->geschlecht,
            'eMailDienstlich' => $this->email,
        ];
    }
}
