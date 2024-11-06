<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeistungResource extends JsonResource
{
    // TODO: To be deleted
    public function toArray($request): array
    {
        $matrix = new MatrixResource($this->schueler->klasse);

        return [
            'id' => $this->id,
            'klasse' => $this->schueler->klasse->kuerzelAnzeige,
            'name' => "{$this->schueler->nachname}, {$this->schueler->vorname}",
            'vorname' => $this->schueler->vorname,
            'nachname' => $this->schueler->nachname,
            'geschlecht' => $this->schueler->geschlecht,
            'fach' => $this->lerngruppe->fach->kuerzelAnzeige,
            'fach_id' => $this->lerngruppe->fach->id,
            'jahrgang' => $this->schueler->jahrgang->kuerzel,
            'lehrer' => $this->lerngruppe->lehrer->pluck('kuerzel')->implode(', '),
            'kurs' => $this->lerngruppe->kursartID !== null ? $this->lerngruppe->bezeichnung : '',
            'note' => $this->note?->kuerzel,
            'istGemahnt' => $this->istGemahnt,
            'mahndatum' => $this->mahndatum,
            'fs' => $this->fehlstundenFach,
            'fsu' => $this->fehlstundenUnentschuldigtFach,
            'fachbezogeneBemerkungen' => $this->fachbezogeneBemerkungen,
            'editable' => [
                'teilnoten' => $matrix->editable_teilnoten,
                'noten' => $matrix->editable_noten,
                'mahnungen' => $matrix->editable_mahnungen,
                'fehlstunden' => $matrix->editable_fehlstunden && $this->toggleable_fehlstunden,
                'fb' => $matrix->editable_fb,
            ],
        ];
    }
}
