<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `NoteResource` class is a JSON resource for formatting and presenting 'Note' data.
 *
 * @package App\Http\Resources\Export
 */
class NoteResource extends JsonResource
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
            'id' => $this->sortierung,
            'kuerzel' => $this->kuerzel,
            'notenpunkte' => $this->notenpunkte,
            'text' => $this->text,
        ];
    }
}
