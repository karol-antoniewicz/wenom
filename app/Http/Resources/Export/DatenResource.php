<?php

namespace App\Http\Resources\Export;

use App\Models\Fach;
use App\Models\Floskelgruppe;
use App\Models\Foerderschwerpunkt;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Lerngruppe;
use App\Models\Note;
use App\Models\Schueler;
use App\Models\Teilleistungsart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DatenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get the data with all relations.
        $schueler = Schueler::with([
            'bemerkung',
            'leistungen' => [
                'note',
            ],
            'lernabschnitt' => [
                'lernbereich1Note', 'lernbereich2Note', 'foerderschwerpunkt1Relation', 'foerderschwerpunkt2Relation',
            ],
        ])
        ->get();

        return [
            'enmRevision' => $this->enmRevision,
            'schulnummer' => $this->schulnummer,
            'schuljahr' => $this->schuljahr,
            'anzahlAbschnitte' => $this->anzahlAbschnitte,
            'aktuellerAbschnitt' => $this->aktuellerAbschnitt,
            'publicKey' => $this->publicKey,
            'lehrerID' => $this->lehrerID,
            'fehlstundenEingabe' => $this->fehlstundenEingabe,
            'fehlstundenSIFachbezogen' => $this->fehlstundenSIFachbezogen,
            'fehlstundenSIIFachbezogen' => $this->fehlstundenSIIFachbezogen,
            'schulform' => $this->schulform,
            'mailadresse' => $this->mailadresse,
            'noten' => NoteResource::collection(Note::all()),
            'foerderschwerpunkte' => FoerderschwerpunkteResource::collection(Foerderschwerpunkt::all()),
            'jahrgaenge' => JahrgangResource::collection(Jahrgang::all()),
            'klassen' => KlasseResource::collection(Klasse::all()),
            'floskelgruppen' => FloskelgruppeResource::collection(Floskelgruppe::all()),
            'lehrer' => LehrerResource::collection(User::lehrer()->get()),
            'faecher' => FachResource::collection(Fach::all()),
            'teilleistungsarten' => TeilleistungsartResource::collection(Teilleistungsart::all()),
            'lerngruppen' => LerngruppeResource::collection(Lerngruppe::all()),
            'schueler' => SchuelerResource::collection($schueler),
        ];
    }
}

