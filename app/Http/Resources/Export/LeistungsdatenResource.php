<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `LeistungsdatenResource` class is a JSON resource for formatting and presenting 'Leistungsdaten' data.
 *
 * @package App\Http\Resources\Export
 */
class LeistungsdatenResource extends JsonResource
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
            'lerngruppenID' => $this->lerngruppe->id,
            'note' => $this->note?->kuerzel,
            'tsNote' => $this->tsNote,
            'noteQuartal' => $this->quartalnote?->kuerzel,
            'tsNoteQuartal' => $this->tsNoteQuartal,
            'istSchriftlich' => $this->istSchriftlich,
            'abiturfach' => $this->abiturfach,
            'fehlstundenFach' => $this->fehlstundenFach,
            'tsFehlstundenFach' => $this->tsFehlstundenFach,
            'fehlstundenUnentschuldigtFach' => $this->fehlstundenUnentschuldigtFach,
            'tsFehlstundenUnentschuldigtFach' => $this->tsFehlstundenUnentschuldigtFach,
			'fachbezogeneBemerkungen' => $this->fachbezogeneBemerkungen,
			'tsFachbezogeneBemerkungen' => $this->tsFachbezogeneBemerkungen,
			'neueZuweisungKursart' => $this->neueZuweisungKursart,
			'istGemahnt' => (bool) $this->istGemahnt,
			'tsIstGemahnt' => $this->tsIstGemahnt,
			'mahndatum' => $this->mahndatum?->format('d.m.Y'),
            'teilleistungen' => TeilleistungResource::collection($this->teilleistungen),
        ];
    }
}
