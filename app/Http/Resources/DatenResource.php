<?php

namespace App\Http\Resources;

use App\Http\Resources\Export\SchuelerResource;
use App\Http\Resources\Export\FachResource;
use App\Http\Resources\Export\FloskelgruppeResource;
use App\Http\Resources\Export\FoerderschwerpunkteResource;
use App\Http\Resources\Export\JahrgangResource;
use App\Http\Resources\Export\KlasseResource;
use App\Http\Resources\Export\LehrerResource;
use App\Http\Resources\Export\NoteResource;
use App\Http\Resources\Export\TeilleistungResource;
use App\Http\Resources\Export\TeilleistungsartResource;
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
            'enmRevision' => 1,
            'schulnummer' => (int) config('wenom.schulnummer'),
            'schuljahr' => 2021,
            'anzahlAbschnitte' => 2,
            'aktuellerAbschnitt' => 2,
            'publicKey' => 'string',
            'lehrerID' => 42,
            'fehlstundenEingabe' => true,
            'fehlstundenSIFachbezogen' => false,
            'fehlstundenSIIFachbezogen' => true,
            'schulform' => 'GY',
            'mailadresse' => 'mail@schule.nrw.de',
            'schueler' => SchuelerResource::collection($schueler),
            //'noten' => NoteResource::collection(Note::all()),
            //'foerderschwerpunkte' => FoerderschwerpunkteResource::collection(Foerderschwerpunkt::all()),
            //'jahrgaenge' => JahrgangResource::collection(Jahrgang::all()),
            //'klassen' => KlasseResource::collection(Klasse::all()),
            //'floskelgruppen' => FloskelgruppeResource::collection(Floskelgruppe::all()),
            //'lehrer' => LehrerResource::collection(User::all()),
            //'faecher' => FachResource::collection(Fach::all()),
            //'teilleistungsarten' => TeilleistungsartResource::collection(Teilleistungsart::all()),
            //'lerngruppen' => LerngruppeResource::collection(Lerngruppe::all()),
        ];
    }
}
