<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `BkAbschlussResource` class is a JSON resource for formatting and presenting 'BkAbschluss' data.
 *
 * @package App\Http\Resources\Export
 */
class BkAbschlussResource extends JsonResource
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
            'hatZulassung' => $this->hatZulassung,
            'hatBestanden' => $this->hatBestanden,
            'hatZulassungErweiterteBeruflicheKenntnisse' => $this->hatZulassungErweiterteBeruflicheKenntnisse,
            'hatErworbenErweiterteBeruflicheKenntnisse' => $this->hatErworbenErweiterteBeruflicheKenntnisse,
            'notePraktischePruefung' => $this->notePraktischePruefung?->ext_id,
            'noteKolloqium' => $this->noteKolloqium?->ext_id,
            'hatZulassungBerufsabschlusspruefung' => $this->hatZulassungBerufsabschlusspruefung,
            'hatBestandenBerufsabschlusspruefung' => $this->hatBestandenBerufsabschlusspruefung,
            'themaAbschlussarbeit' => $this->themaAbschlussarbeit,
            'istVorhandenBerufsabschlusspruefung' => $this->istVorhandenBerufsabschlusspruefung,
            'noteFachpraxis' => $this->noteFachpraxis?->ext_id,
            'istFachpraktischerTeilAusreichend' => $this->istFachpraktischerTeilAusreichend,
            'faecher' => 123
        ];
    }
}
