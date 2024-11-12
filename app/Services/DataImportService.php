<?php

namespace App\Services;

use App\Models\{
    Daten, Bemerkung, Fach, Floskelgruppe, Floskel, Foerderschwerpunkt, Jahrgang, Klasse, Leistung, Lernabschnitt,
    Lerngruppe, Note, Schueler, User, Teilleistungsart, Teilleistung,
};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Collection, Model};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\{Arr, Str};
use Illuminate\Support\Facades\Validator;

class DataImportService
{
    private array $status = [
        'errors' => [],
        'success' => [],
    ];

    private array $existingNoten = [];
    private array $existingFoerderschwerpunkte = [];

    /**
     * Class constructor
     *
     * @param array $data
     */
    public function __construct(private array $data = [])
    {
    }

    /**
     * Import execution
     *
     * @return JsonResponse
     */
    public function execute(): static
    {
        $this->importDaten();
        $this->importLehrer();
        $this->importJahrgaenge();
        $this->importKlassen();
        $this->importNoten();
        $this->importFoerderschwerpunkte();
        $this->importFaecher();
        $this->importLerngruppen();
        $this->importTeilleistungsarten();
        $this->importSchueler();
        $this->importLeistungsdaten();
        $this->importBemerkungen();
        $this->importFloskelgruppen();

        return $this;
    }

    /**
     * Retrieve the response
     *
     * @return JsonResponse
     */
    public function response(): JsonResponse
    {
        $errorMessages = session('import-error', []);
        $successMessages = session('import-success', []);

        foreach ($errorMessages as $errorMessage) {
            $this->status['error'][][] = [
                'message' => $errorMessage['message'],
                'data' => $errorMessage['data'] ?? null,
                'errors' => $errorMessage['errors'] ?? null,
            ];
        }

        foreach ($successMessages as $message) {
            $this->status['success'][][] = [
                'message' => $message['message'],
                'data' => $message['data'] ?? null,
            ];
        }

        return response()->json($this->status);
    }

    /**
     * Creates or updates the Daten model.
     *
     * @return void
     */
    public function importDaten(): void
    {
        Daten::firstOrCreate()->update([
            'enmRevision' => $this->data['enmRevision'],
            'schulnummer' => $this->data['schulnummer'],
            'schuljahr' => $this->data['schuljahr'],
            'anzahlAbschnitte' => $this->data['anzahlAbschnitte'],
            'aktuellerAbschnitt' => $this->data['aktuellerAbschnitt'],
            'publicKey' => $this->data['publicKey'],
            'lehrerID' => $this->data['lehrerID'],
            'fehlstundenEingabe' => $this->data['fehlstundenEingabe'],
            'fehlstundenSIFachbezogen' => $this->data['fehlstundenSIFachbezogen'],
            'fehlstundenSIIFachbezogen' => $this->data['fehlstundenSIIFachbezogen'],
            'schulform' => $this->data['schulform'],
            'mailadresse' => $this->data['mailadresse'],
        ]);
    }

    /**
     * Creates or updates the Lehrer model.
     * The id is stored under "ext_id" since we have administrator users as well.
     *
     * @return void
     */
    public function importLehrer(): void
    {
        $key = 'lehrer';

        if ($this->keyMissingOrEmpty($this->data, $key, 'global')) {
            return;
        }

        collect($this->data[$key])->each(fn (array $row): User => User::updateOrCreate(['ext_id' => $row['id']], $row));
    }

    /**
     * Creates "Jahrgange". (tested)
     *
     * @return void
     */
    public function importJahrgaenge(): void
    {
        $key = 'jahrgaenge';

        if ($this->keyMissingOrEmpty($this->data, $key, 'global')) {
            return;
        }

        $jahrgaenge = Jahrgang::all();

        collect($this->data[$key])
            // Check if "Id" is valid
            ->filter(fn (array $row): bool => $this->validId($row, 'id', $jahrgaenge, $key))
            // Check if "Kuerzel" is valid and unique
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'kuerzel', $key))
            ->filter(fn (array $row): bool => $this->isUnique($row, $jahrgaenge, 'kuerzel', $key))
            // Check if "Sortierung" is valid and unique
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'sortierung', $key))
            ->filter(fn (array $row): bool => $this->isUnique($row, $jahrgaenge, 'sortierung', $key))
            // Check if other columns is valid
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'kuerzelAnzeige', $key))
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'stufe', $key))
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'beschreibung', $key))
            ->each(fn (array $array) => Jahrgang::create($array));
    }

    /**
     * Creates the Klasse model. [tested]
     * The model will not be updated with future requests.
     * After creating the model, the Lehrer model relationships are created.
     * The relationships are only being set once, and will not trigger at consecutive requests.
     *
     * @return void
     */
    public function importKlassen(): void
    {
        $key = 'klassen';

        if ($this->keyMissingOrEmpty($this->data, $key, 'global')) {
            return;
        }

        // Existing "Klassenlehrer"
        $klassenlehrer = fn (array $row): array => User::query()
            ->whereIn('ext_id', (array) $row['klassenlehrer'])
            ->pluck('id')
            ->toArray();


        $klassen = Klasse::all();
        $jahrgaenge = Jahrgang::all();

        collect($this->data[$key])
            // Check if the "Klassenlehrer" relation is present and valid
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'klassenlehrer', $key))
            ->filter(fn (array $row): bool => $this->isValidArray($row, 'klassenlehrer', $key))
            // Check if "Klasse" exists and is valid
            ->filter(fn (array $row): bool => $this->validId($row, 'id', $klassen, $key, unique: true))
            // Check if "Kuerzel" exists and is valid
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'kuerzel', $key))
            ->filter(fn (array $row): bool => $this->isUnique($row, $klassen, 'kuerzel', $key))
            // Check if "Sortierung" exists and is valid
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'sortierung', $key))
            ->filter(fn (array $row): bool => $this->isUnique($row, $klassen, 'sortierung', $key))
            // Check if the "Jahrgang" relation is present and valid
            ->filter(fn (array $row): bool => $this->validId($row, 'idJahrgang', $klassen, $key))
            ->filter(fn (array $row): bool => $this->hasRelation($row, 'idJahrgang', $jahrgaenge, $key))
            // Create the resource
            ->each(function (array $row) use ($klassenlehrer): void {
                $klasse = Klasse::create(Arr::except($row, 'klassenlehrer'));
                $klasse->klassenlehrer()->sync($klassenlehrer($row));
            });
    }

    /**
     * Creates the Note model. (tested)
     * The model will not be updated with future requests.
     * Resources with an negative id are filtered out. Relatable models are nullable.
     *
     * TODO: All Models are stored in an array to be called by ID in different resources.
     *
     * @return void
     */
    public function importNoten(): void
    {
        $key = 'noten';

        if ($this->keyMissingOrEmpty($this->data, $key, 'global')) {
            return;
        }

        $noten = Note::all();

        // ID in SVWS is for ordering the records, in wenom it has to be remapped to correct key.
        $remapSortierung = function (array $row): array {
            $row['sortierung'] = $row['id'];
            unset($row['id']);
            return $row;
        };

        collect($this->data[$key])
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'id', $key, nullable: false, expectedInteger: true))
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'kuerzel', $key))
            ->filter(fn (array $row): bool => $this->isUnique($row, $noten, 'kuerzel', $key))
            ->map($remapSortierung)
            ->each(fn (array $row): Note => Note::create($row));

        $this->existingNoten = $this->getExistingNoten(); // TODO: To be removed when IMPORT_LERNABSCHNITTE IS FIXED
    }

    /**
     * Creates the Foerderschwerpunkt model. [tested]
     * The model will not be updated with future requests.
     *
     * TODO: All Models are stored in an array to be called by ID in different resources.
     *
     * @return void
     */
    public function importFoerderschwerpunkte(): void
    {
        $key = 'foerderschwerpunkte';

        if ($this->keyMissingOrEmpty($this->data, $key, 'global')) {
            return;
        }

        $foerderschwerpunkte = Foerderschwerpunkt::all();

        // ID in SVWS is for ordering the records, in wenom it has to be remapped to correct key.
        $remapSortierung = function (array $row): array {
            $row['sortierung'] = $row['id'];
            unset($row['id']);
            return $row;
        };

        collect($this->data[$key])
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'id', $key, nullable: false, expectedInteger: true))
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'kuerzel', $key))
            ->filter(fn (array $row): bool => $this->isUnique($row, $foerderschwerpunkte, 'kuerzel', $key))
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'beschreibung', $key))
            ->map($remapSortierung)
            ->each(fn (array $row): Foerderschwerpunkt => Foerderschwerpunkt::create($row));

        $this->existingFoerderschwerpunkte = $this->getExistingFoerderschwerpunkte(); // TODO: To be removed
    }

    /**
     * Creates the Fach model. (tested)
     *
     * @return void
     */
    public function importFaecher(): void
    {
        $key = 'faecher';

        if ($this->keyMissingOrEmpty($this->data, $key, 'global')) {
            return;
        }

        $faecher = Fach::all();

        collect($this->data[$key])
            // Check if "Fach" already exists and is valid
            ->filter(fn (array $row): bool => $this->validId($row, 'id', $faecher, $key, unique: true))
            // Check if "Kuerzel" already exists and is valid
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'kuerzel', $key))
            ->filter(fn (array $row): bool => $this->isUnique($row, $faecher, 'kuerzel', $key))
            // Check if "Sortierung" already exists and is valid
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'sortierung', $key))
            ->filter(fn (array $row): bool => $this->isUnique($row, $faecher, 'sortierung', $key))
            // Check other fields if are valid
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'istFremdsprache', $key))
            // Create the resource
            ->each(fn (array $row): Fach => Fach::create($row));
    }

    /**
     * Creates the Lerngruppe model.
     * The model will not be updated with future requests.
     * Related Lehrer models will be created only if the Lerngruppe model was recently created.
     * A lerngruppe either belongs to a Klasse model or is a Kurs. Depending on the presence of kursartID.
     *
     * @return void
     */
    public function importLerngruppen(): void
    {
        if ($this->keyMissingOrEmpty($this->data, 'lerngruppen', 'global')) {
            return;
        }

        $lerngruppen = Lerngruppe::all();

        // Existing "Klassenlehrer"
        $klassenlehrer = fn (array $row): array => User::query()
            ->whereIn('ext_id', (array) $row['lehrerID'])
            ->pluck('id')
            ->toArray();

        collect($this->data['lerngruppen'])
            ->filter(fn (array $array): bool => $this->hasValidId($array, 'lerngruppen', $lerngruppen))
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'lerngruppen', 'kID'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'lerngruppen', 'bezeichnung'))
            // Check if "kID" is present and properly formatted.
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'lerngruppen', 'wochenstunden'))
            // Check if relate "Fach exists"
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'lerngruppen', 'fachID'))
            ->filter(function (array $array): bool {
                if (Fach::where(['id' => $array['fachID']])->doesntExist()) {
                    $this->setStatus('lerngruppe', 'Fach mit id ' . $array['fachID'] . ' existiert nicht.', $array['id']);
                    return false;
                }

                return true;
            })
            // Remap the keys and unset unused valued
            ->map(function (array $array): array {
                $array['fach_id'] = $array['fachID'];
                unset($array['fachID']);
                return $array;
            })
            // Check if "kursartID" is set to NULL which indicates this "Lerngruppe" is assigned to a class.
            // Check if "klasse" exists
            ->filter(function (array $array): bool {
                if (!is_null($array['kursartID'])) {
                    return true;
                }

                // Check if "Klasse" exists
                if (Klasse::where(['id' => $array['kID']])->doesntExist()) {
                    $this->setStatus('lerngruppe', 'Klasse mit id ' . $array['kID'] . ' existiert nicht.', $array['id']);
                    return false;
                }

                return true;
            })
            // Check if "kursartID" is set to NULL which indicates this "Lerngruppe" is assigned to a class.
            // Therefor we dont need the kursartID anymore.
            ->map(function (array $array): array {
                if (is_null($array['kursartID'])) {
                    $array['klasse_id'] = $array['kID'];
                    unset($array['kursartID']);
                }

                return $array;
            })
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'lerngruppen', 'lehrerID'))
            ->filter(function (array $array): bool {
                // Filter out non integer values
                $lehrerIds = array_filter($array['lehrerID'], fn (int|string|null $value): bool => is_int($value));

                // Check if there are any IDs in the element
                if (0 === count($lehrerIds)) {
                    $this->setStatus('lerngruppen', '"lehrerID" ist leer.', $array['id']);
                    return false;
                }

                // Check if all users with corresponding lehrerID exists. If not, log and continue.
                if (User::whereIn('ext_id', $lehrerIds)->count() !== count($lehrerIds)) {
                    $this->setStatus('lerngruppen', 'Nicht alle Lehrer existieren bereits.', $array['id']);
                    return false;
                }

                return true;
            })
            ->each(function (array $array) use ($klassenlehrer): void {
                $lerngruppe = Lerngruppe::create(Arr::except($array, 'lehrerID'));
                $lerngruppe->lehrer()->attach($klassenlehrer($array));
            });
    }


    /**
     * Import Floskelgruppen (testd)
     *
     * @return void
     */
    public function importFloskelgruppen(): void
    {
        if ($this->keyMissingOrEmpty($this->data, 'floskelgruppen', 'global')) {
            return;
        }

        $floskelgruppen = Floskelgruppe::all();
        $jahrgaenge = Jahrgang::all();
        $faecher = Fach::all();

        collect($this->data['floskelgruppen'])
            // Check if "Kuerzel" is valid and unique
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'kuerzel', 'floskelgruppen', ))
            ->filter(fn (array $row): bool => $this->isUnique($row, $floskelgruppen, 'kuerzel', 'floskelgruppen'))
            // Check if following fields are valid
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'bezeichnung', 'floskelgruppen'))
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'hauptgruppe', 'floskelgruppen'))
            // Check if "Floskeln" are present
            ->each(function (array $row) use ($jahrgaenge, $faecher): void {
                $data = Arr::except($row, 'floskeln');
                $floskelgruppe = Floskelgruppe::create($data);
                $this->importFloskeln($floskelgruppe, $row, $jahrgaenge, $faecher);
            });
    }

    /**
     * Import floskeln (tested)
     *
     * @param Floskelgruppe $floskelgruppe
     * @param array $array
     * @param Collection $jahrgaenge
     * @param Collection $faecher
     * @return void
     */
    private function importFloskeln(
        Floskelgruppe $floskelgruppe,
        array $array = [],
        Collection $jahrgaenge,
        Collection $faecher
    ): void {
        if ($this->keyMissingOrEmpty($array, 'floskeln', 'global')) {
            return;
        }

        $floskeln = Floskel::all();

        collect($array['floskeln'])
            // Check if "Kuerzel" is valid and unique
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'kuerzel', 'floskeln'))
            ->filter(fn (array $row): bool => $this->isUnique($row, $floskeln, 'kuerzel', 'floskeln'))
            // Check if "Text" is valid
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'text', 'floskeln'))
            // Check if "Jahrgang" is valid and exists
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'jahrgangID', 'floskeln', nullable: true))
            ->filter(
                fn (array $row): bool => $this->hasRelation(
                    $row,
                    'jahrgangID',
                    $jahrgaenge,
                    'floskeln',
                    'Jahrgang',
                    nullable: true
                )
            )
            // Check if "Fach" is valid and exists
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'fachID', 'floskeln', nullable: true))
            ->filter(
                fn (array $row): bool =>
                $this->hasRelation($row, 'fachID', $faecher, 'floskeln', 'Fach', nullable: true)
            )
            // Check if "Niveau" is valid
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'niveau', 'floskeln', nullable: true))
            // Remap fields to Laravel notation
            ->map(function (array $row): array {
                $row['jahrgang_id'] = $row['jahrgangID'];
                $row['fach_id'] = $row['fachID'];

                unset($row['jahrgangID'], $row['fachID']);

                return $row;
            })
            // Create the "Floskel"
            ->each(fn (array $row): Floskel => $floskelgruppe->floskeln()->create($row));
    }

    /**
     * Creates the Schueler model. The model will not be updated with future requests.
     *
     * @return void
     */
    public function importSchueler(): void
    {
        $key = 'schueler';

        if ($this->keyMissingOrEmpty($this->data, $key, 'global')) {
            return;
        }

        $schueler = Schueler::all();
        $jahrgaenge = Jahrgang::all();
        $klassen = Klasse::all();

        collect($this->data[$key])
            ->filter(fn (array $row): bool => $this->validId($row, 'id', $schueler, $key))
            ->filter(fn (array $row): bool => $this->hasRelation($row, 'jahrgangID', $jahrgaenge, $key))
            ->filter(fn (array $row): bool => $this->hasRelation($row, 'klasseID', $klassen, $key))
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'geschlecht', $key))
            // Remap the keys and unset unused valued
            ->map(function (array $row): array {
                $row['klasse_id'] = $row['klasseID'];
                $row['jahrgang_id'] = $row['jahrgangID'];
                $row['geschlecht'] = $this->gender($row, Schueler::GENDERS);
                unset($row['klasseID'], $row['jahrgangID'], $row['ankreuzkompetenzen']);

                // TODO: Temporary unset. TO BE CLEARED
                unset($row['sprachenfolge'], $row['zp10'], $row['bkabschluss']);

                return $row;
            })
            ->each(function (array $row): void {
                $schueler = Schueler::create(Arr::except($row, ['bemerkungen', 'lernabschnitt', 'leistungsdaten']));
                $this->importLernabschnitte($schueler, $row['lernabschnitt']);
            });
    }


    /**
     * Creates the Leistung model. The model will be updated with future requests.
     * The timestamp will be compared to check if the data was updated on the SVWS server.
     * TODO: ^^^ Date missing. To be cleared which timestamp for which columns should be valid.
     *
     * @return void
     */
    public function importLeistungsdaten(): void
    {
        // Check if "Schueler" are set
        if ($this->keyMissingOrEmpty($this->data, 'schueler', 'leistungsdaten')) {
            return;
        }

        // Prefetch some data
        $lerngruppen = Lerngruppe::all()->pluck('id', 'id')->toArray();
        $leistungen = Leistung::all()->pluck('id', 'id')->toArray();
        $noten = Note::all()->pluck('id', 'kuerzel')->toArray();
        $schueler = Schueler::all()->pluck('id', 'id')->toArray();

        collect($this->data['schueler'])
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'leistungsdaten', 'leistungsdaten'))
            ->filter(fn (array $array): bool => in_array($array['id'], $schueler))
            ->each(function (array $schueler) use ($noten, $leistungen, $lerngruppen): void {
                collect($schueler['leistungsdaten'])
                    ->filter(fn (array $array): bool => $this->hasValidInt($array, 'leistungsdaten', 'id'))
                    // Check if "lerngruppenID is set
                    ->filter(fn (array $array): bool => $this->hasValidValue($array, 'leistungsdaten', 'lerngruppenID'))
                    // Chech if a "Lerngruppe" with the id exists
                    ->filter(fn (array $array): bool => array_key_exists($array['lerngruppenID'], $lerngruppen))
                    // Check if "Note is set"
                    ->filter(fn (array $array): bool => array_key_exists('note', $array))
                    ->filter(
                        fn (array $array): bool =>
                        in_array($array['note'], [null, '']) || array_key_exists($array['note'], $noten)
                    )
                    // Check if "Quartals Note is set"
                    ->filter(fn (array $array): bool => array_key_exists('noteQuartal', $array))
                    ->filter(
                        fn (array $array): bool =>
                        in_array($array['noteQuartal'], [null, '']) || array_key_exists($array['noteQuartal'], $noten)
                    )
                    // Perform the upsert
                    ->each(fn (array $array) => $this->upsert($array, $schueler, $noten));
            });
    }

    // Perfom the upsert
    private function upsert(array $array, $schueler, $noten): void
    {
        $teilleistungen = $array;
        // Remap some fields to Laravel notation
        $array['note_id'] = $noten[$array['note']] ?? null;
        $array['note_quartal_id'] = $noten[$array['noteQuartal']] ?? null;
        $array['lerngruppe_id'] = $array['lerngruppenID'];
        $excluded = [
            'lerngruppenID', 'note', 'teilleistungen', 'noteQuartal',
        ];
        foreach($excluded as $current) {
            unset($array[$current]);
        }
        $leistung = Leistung::firstOrNew(
            ['id' => $array['id'], 'schueler_id' => $schueler['id']],
            $array
        );

        // Check if timestamps for some fields are latter than the ones stored in DB.
        $this->updateWhenRecent($array, $leistung, 'note_id', 'tsNote');
        $this->updateWhenRecent($array, $leistung, 'note_quartal_id', 'tsNoteQuartal');
        $this->updateWhenRecent($array, $leistung, 'fehlstundenFach', 'tsFehlstundenFach');
        $this->updateWhenRecent($array, $leistung, 'fehlstundenUnentschuldigtFach', 'tsFehlstundenUnentschuldigtFach');
        $this->updateWhenRecent($array, $leistung, 'fachbezogeneBemerkungen', 'tsFachbezogeneBemerkungen');
        $this->updateWhenRecent($array, $leistung, 'istGemahnt', 'tsIstGemahnt');

        $leistung->save();
        $key = 'teilleistungen';
        if (!array_key_exists($key, $teilleistungen)) {
            return;
        }

        if (count($teilleistungen[$key]) == 0) {
            return;
        }
        $this->importTeilleistungen($leistung, $teilleistungen[$key], $key);
    }

    /**
     * Import Teillesitungen
     *
     * @param Leistung $leistung
     * @param array $array
     * @param string $key
     */
    private function importTeilleistungen(Leistung $leistung, array $array, string $key): void
    {
        $teilleistungsarten = Teilleistungsart::all()->pluck('id', 'id')->toArray();
        $noten = Note::all()->pluck('id', 'kuerzel')->toArray();

        $uniqueArray = $this->uniqueTeilleistungen($array);
        collect($uniqueArray)
            // ID
            ->filter(fn (array $row): bool => $this->hasValidInt($row, $key, 'id'))
            // Teilleisungsarten
            ->filter(fn (array $row): bool => $this->hasValidInt($row, $key, 'artID'))
            ->filter(
                fn (array $row): bool =>
                $this->hasValidRelation($row, 'artID', $teilleistungsarten, $key, 'Teilleistungsart')
            )
            ->filter(fn (array $row): bool => $this->hasValidValue($row, $key, 'tsArtID'))
            ->map(function (array $row) use ($teilleistungsarten): array {
                $row['teilleistungsart_id'] = $this->getValueFromArray($row, 'artID', $teilleistungsarten);
                unset($row['artID']);
                return $row;
            })
            // Noten
            ->filter(fn (array $row): bool => $this->hasValidRelation($row, 'note', $noten, $key, 'Note', true))
            ->filter(fn (array $row): bool => $this->hasValidValue($row, $key, 'tsNote'))
            ->map(function (array $row) use ($noten): array {
                $row['note_id'] = $this->getValueFromArray($row, 'note', $noten);
                unset($row['note']);
                return $row;
            })
            // Bemerkung
            ->filter(
                fn (array $row): bool =>
                $this->isValidValue($row, 'bemerkung', $key, nullable: true, emptable: true)
            )
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'tsBemerkung', $key))
            // Datum
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'datum', $key, nullable: true, emptable: true))
            ->filter(fn (array $row): bool => $this->isValidValue($row, 'tsDatum', $key))
            ->each(function (array $row) use ($leistung): void {
                $model = Teilleistung::firstOrNew(['id' => $row['id']], $row);
                $model->leistung_id = $leistung->id;

                $this->updateWhenRecent($row, $model, 'note_id', 'tsNote');
                $this->updateWhenRecent($row, $model, 'teilleistungsart_id', 'tsArtID');
                $this->updateWhenRecent($row, $model, 'bemerkung', 'tsBemerkung');
                $this->updateWhenRecent($row, $model, 'datum', 'tsDatum');

                $model->save();
            });
    }

    /**
     * Imports only unique Teilleistungen based on artID.
     * If there are multiple same artID, takes the newer one
     *
     * @param array $array
     * @return array
     */
    private function uniqueTeilleistungen(array $array): array
    {
        $unique = [];

        foreach ($array as $item) {
            $id = $item['artID'];

            // Set the id if not already present
            if (!isset($unique[$id])) {
                $unique[$id] = $item;
            }

            if ($item['tsArtID'] > $unique[$id]['tsArtID']) {
                $unique[$id] = $item;

                $this->setStatus(
                    $item,
                    'teilleistung',
                    'Teilleistung hat einen neueren Zeitstempel und ersetzt einen älteren'
                );
            }
        }

        // Convert the associative array back to a simple array
        return array_values($unique);
    }


    /** Import "Bemerkungen" */
    public function importBemerkungen(): void
    {
        $key = 'bemerkungen';

        if ($this->keyMissingOrEmpty($this->data, 'schueler', 'global')) {
            return;
        }

        $schueler = Schueler::all();

        $isValidValue = fn (array $row, string $column): bool => $this->isValidValue(
            $row[$key],
            $column,
            $key,
            nullable: true,
            emptable: true,
        );

        collect($this->data['schueler'])
            // Check if given "Schueler" exist
            ->filter(fn (array $row): bool => $this->hasRelation($row, 'id', $schueler, $key))
            // Check if the "Bemerkungen" for the "Schueler" are set
            ->filter(fn (array $row): bool => $this->isValidArray($row, $key, $key))
            // Check if updatable data is existing and valid
            ->filter(fn (array $row): bool => $isValidValue($row, 'ASV'))
            ->filter(fn (array $row): bool => $this->isValidValue($row[$key], 'tsASV', $key))
            ->filter(fn (array $row): bool => $isValidValue($row, 'AUE'))
            ->filter(fn (array $row): bool => $this->isValidValue($row[$key], 'tsAUE', $key))
            ->filter(fn (array $row): bool => $isValidValue($row, 'ZB'))
            ->filter(fn (array $row): bool => $this->isValidValue($row[$key], 'tsZB', $key))
            ->filter(fn (array $row): bool => $isValidValue($row, 'individuelleVersetzungsbemerkungen'))
            ->filter(fn (array $row): bool => $this->isValidValue($row[$key], 'tsIndividuelleVersetzungsbemerkungen', $key))
            // Iteare rows to create or update "Bemerkung"
            ->each(function (array $row) use ($key): void {
                // Check if "Bemerkung" already exists. If not, create a new one.
                $bemerkung = Bemerkung::firstOrNew(['schueler_id' => $row['id']]);
                $data = $row[$key];

                // Set these values only while creating the Bemerkung
                if ($bemerkung->wasRecentlyCreated) {
                    $bemerkung->LELS = $data['LELS'];
                    $bemerkung->schulformEmpf = $data['schulformEmpf'];
                    $bemerkung->foerderbemerkungen = $data['foerderbemerkungen'];
                }

                $this->updateWhenRecent($data, $bemerkung, 'ASV', 'tsASV');
                $this->updateWhenRecent($data, $bemerkung, 'AUE', 'tsAUE');
                $this->updateWhenRecent($data, $bemerkung, 'ZB', 'tsZB');
                $this->updateWhenRecent(
                    $data,
                    $bemerkung,
                    'individuelleVersetzungsbemerkungen',
                    'tsIndividuelleVersetzungsbemerkungen'
                );

                $bemerkung->save();
            });
    }

    /**
     * Creates the Lernabschnitt model.
     * TODO: It is not yet clear if the model will not be updated with future requests or will.
     * TODO: Still waiting for the information if the 4 fields will have the `id` instead of the `kuerzel`
     *
     * @param Schueler $schueler
     * @param array $data
     * @return void
     */
    private function importLernabschnitte(Schueler $schueler, array $data): void
    {
        if ($data) { // TODO: To be updated if noteID is available // TODO: Idea to put into schueler

            if (!$this->validId($data, 'id', Lernabschnitt::all(), 'lernabschnitt', unique: true)) {
                return;
            }

            $lernabschnitt = Lernabschnitt::firstOrNew(['id' => $data['id']]);
            $lernabschnitt->schueler_id = $schueler->id;

            $this->updateWhenRecent($data, $lernabschnitt, 'fehlstundenGesamt', 'tsFehlstundenGesamt');
            $this->updateWhenRecent(
                $data,
                $lernabschnitt,
                'fehlstundenGesamtUnentschuldigt',
                'tsFehlstundenGesamtUnentschuldigt'
            );

            $lernabschnitt->foerderschwerpunkt1 = $this->getValueFromArray(
                $data,
                'foerderschwerpunkt1',
                $this->existingFoerderschwerpunkte
            );

            $lernabschnitt->foerderschwerpunkt2 = $this->getValueFromArray(
                $data,
                'foerderschwerpunkt2',
                $this->existingFoerderschwerpunkte
            );

            $lernabschnitt->lernbereich1note = $this->getValueFromArray(
                $data,
                'lernbereich1note',
                $this->existingNoten
            );

            $lernabschnitt->lernbereich2note = $this->getValueFromArray(
                $data,
                'lernbereich2note',
                $this->existingNoten
            );

            $lernabschnitt->pruefungsordnung = $data['pruefungsordnung'] ?? 'Lorem ipsum'; // TODO: Check with customer

            $lernabschnitt->save();
        }
    }

    /**
     * Import "Schueler" "Bemerkungen"
     *
     * @param Schueler $schueler
     * @param array $data
     * @return void
     */
    public function importTeilleistungsarten(): void
    {
        $key = 'teilleistungsarten';

        if ($this->keyMissingOrEmpty($this->data, $key, 'global')) {
            return;
        }

        $collection = Teilleistungsart::all();

        collect($this->data[$key])
            ->filter(fn (array $array): bool => $this->hasValidId($array, $key, $collection))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, $key, 'bezeichnung'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, $key, $collection, 'bezeichnung'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, $key, 'bezeichnung'))
            ->filter(fn (array $array): bool => $this->hasValidInt($array, $key, 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, $key, $collection, 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, $key, 'gewichtung'))
            ->each(fn (array $array): Teilleistungsart => Teilleistungsart::create($array));
    }

    /**
     * Fill selected row with a value only if the timestamp is newer than the one stored in the database.
     * If value parameter is provided, value will be used instead of the column value
     *
     * @param array $data
     * @param Model $model
     * @param string $column
     * @param string $tsColumn
     * @param int|null $value
     * @return Model
     */
    private function updateWhenRecent(
        array $data,
        Model &$model,
        string $column,
        string $tsColumn,
        int|null $value = null
    ): Model {
        $timestamp = Carbon::parse($data[$tsColumn]);

        if ($model->$tsColumn == null) {
            $model->$tsColumn = $data[$tsColumn];
            return $model;
        }

        if ($timestamp->gt($model->$tsColumn)) {
            $model->$column = $value ?? $data[$column];
            $model->$tsColumn = $timestamp->format('Y-m-d H:i:s.u');

            $this->setSuccessStatus(
                'timestamp',
                'Datensatz wurde aktualisiert da es einen neuren Timestamp hat',
                $data,
            );
        }

        return $model;
    }

    /**
     * Get value from array
     *
     * @param array $data
     * @param string $column
     * @param array $collection
     * @return int|string|null
     */
    private function getValueFromArray(array $data, string $column, array $collection): int|string|null
    {
        if ($data[$column] !== null && array_key_exists($data[$column], $collection)) {
            return $collection[$data[$column]];
        }

        return null;
    }

    /**
     * Trim whitespace
     *
     * @param string $text
     * @return string
     */
    private function trimWhitespaces(string $text): string
    {
        return trim(preg_replace('/\s+/', ' ', $text));
    }

    /**
     * Get gender
     *
     * @param array $data
     * @param array $allowed
     * @return string
     */
    private function gender(array $data, array $allowed): string
    {
        if (array_key_exists('geschlecht', $data) && in_array($data['geschlecht'], $allowed)) {
            return $data['geschlecht'];
        }

        return User::FALLBACK_GENDER;
    }

    /**
     * Format email
     * If none provided, generate a random one
     *
     * @param string|null $email
     * @return string
     */
    private function formatEmail(string|null $email): string
    {
        $validator = Validator::make(
            ['email' => $email],
            ['email' => ['required', 'email:rfc,dns']]
        );

        if ($validator->valid()) {
            return strtolower($email);
        }

        return sprintf('%s@%s', Str::random(32), Str::random(32));
    }

    /**
     * Set update or create success status
     *
     * @param string $key
     * @param bool $recentlyCreated
     * @param array|string|int|null $data
     */
    private function setUpdateOrCreateSuccessStatus(string $key, bool $recentlyCreated, array|string|int|null $data = null): void
    {
        $message = sprintf('%s Ressource wurde erfolgreich %s.', ucfirst($key), $recentlyCreated ? 'angelegt' : 'aktualisiert');
        $this->setStatus($key, $message, $data, 'success');
    }

    private function setSuccessStatus(string $id, string $message, array|string|int|null $data = null): void
    {
        $this->setStatus($id, $message, $data, 'success');
    }

    private function setStatus(string $id, string $message, array|string|int|null $data = null, string $type = 'errors'): void
    {
        $this->status[$type][$id][] = [
            'message' => $message,
            'data' => $data,
        ];
    }

    // TO BE REMOVED, NEEDS SVWS SERVER UPDATES
    private function getExistingFoerderschwerpunkte(): array
    {
        return Foerderschwerpunkt::query()
            ->orderBy('kuerzel')
            ->pluck('id', 'kuerzel')
            ->toArray();
    }

    private function getExistingNoten(): array
    {
        return Note::query()
            ->orderBy('kuerzel')
            ->pluck('id', 'kuerzel')
            ->toArray();
    }

    /**
     * Checks if given element has invalid id
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @return bool
     */
    private function hasInvalidId(array $row, string $context, Collection $existing): bool
    {
        if (!array_key_exists('id', $row)) {
            $this->setStatus($row, $context, '"id" nicht vorhanden');
            return true;
        }

        if (!is_int($row['id'])) {
            $this->setStatus($row, $context, '"id" ist keine Zahl');
            return true;
        }

        if ($row['id'] < 0) {
            $this->setStatus($row, $context, '"id" ist negativ');
            return true;
        }

        if ($existing->contains($row['id'])) {
            $this->setStatus($row, $context, 'Ressource mit diesen "id" existiert bereits');
            return true;
        }

        return false;
    }

    /**
     * Checks if given element has invalid kuerzel
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @return bool
     */
    private function hasInvalidKuerzel(array $row, string $context, Collection $existing): bool
    {
        if (!array_key_exists('kuerzel', $row)) {
            $this->setStatus($row, $context, '"keurzel" nicht vorhanden');
            return true;
        }

        if (in_array($row['kuerzel'], [null, ''])) {
            $this->setStatus($row, $context, '"keurzel" ist leer');
            return true;
        }

        if ($existing->filter(fn (mixed $item): bool => $item['kuerzel'] ==  $row['kuerzel'])->count() > 0) {
            $this->setStatus($row, $context, 'Ressource mit diesen "kuerzel" existiert bereits');
            return true;
        }

        return false;
    }

    /**
     * Checks if given element has invalid sortierung
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @return bool
     */
    private function hasInvalidSortierung(array $row, string $context, Collection $existing): bool
    {
        if (!array_key_exists('sortierung', $row)) {
            $this->setStatus($row, $context, '"sortierung" nicht vorhanden');
            return true;
        }

        if (is_null($row['sortierung'])) {
            $this->setStatus($row, $context, '"sortierung" ist leer');
            return true;
        }

        if (!is_int($row['sortierung'])) {
            $this->setStatus($row, $context, '"sortierung" ist keine Zahl');
            return true;
        }

        if ($existing->filter(fn (mixed $item): bool => $item['sortierung'] == $row['sortierung'])->count() > 0) {
            $this->setStatus($row, $context, 'Ressource mit diesen "sortierung" bereits existiert');
            return true;
        }

        return false;
    }

    /**
     * Checks if given element has a key mising
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @return bool
     */
    private function hasValidKey(array $row, string $key, string $context): bool
    {
        if (array_key_exists($key, $row)) {
            return true;
        }

        $this->setStatus($context, "{$key} nicht vorhanden", $row);
        return false;
    }

    /**
     * Checks if given element has a key mising
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @return bool
     */
    private function hasMissingKey(array $row, string $key, string $context): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($row, "{$key} nicht vorhanden", $context);
            return true;
        }

        return false;
    }

    /**
     * Checks if given element has invalid key
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @return bool
     */
    private function keyMissingOrEmpty(array $row, string $key, string $context): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "{$key} nicht vorhanden", $row);
            return true;
        }

        if (is_null($row[$key])) {
            $this->setStatus($context, "{$key} ist leer", $row);
            return true;
        }

        return false;
    }

    /**
     * Checks if given element has invalid integer
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @return bool
     */
    private function hasInvalidInt(array $row, string $key, string $context): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($row, $context, "{$key} nicht vorhanden");
            return true;
        }

        if (is_null($row[$key])) {
            $this->setStatus($row, $context, "{$key} ist leer");
            return true;
        }


        if (!is_int($row[$key])) {
            $this->setStatus($row, $context, "{$key} kein Zahl");
            return true;
        }

        if (0 > $row[$key]) {
            $this->setStatus($row, $context, "{$key} ist negativ");
            return true;
        }

        return false;
    }

    /**
     * Checks if given element has valid id
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @param string $key
     * @return bool
     */
    private function hasValidId(array $row, string $context, Collection $existing, string $key = 'id', bool $unique = true): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "'{$key}' nicht vorhanden");
            return false;
        }

        $id = $row[$key];

        if (!is_int($id)) {
            $this->setStatus($context, "'{$key}' ist keine Zahl", $id);
            return false;
        }

        if ($id <= 0) {
            $this->setStatus($context, "'{$key}' ist nicht positiv", $id);
            return false;
        }

        if ($unique && $existing->contains($id)) {
            $this->setStatus($context, "Ressource mit diesen '{$key}' existiert bereits", $id);
            return false;
        }

        return true;
    }

    /**
     * Checks if given element has valid key
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @param string $key
     * @return bool
     */
    private function hasValidValue(array $row, string $context, string $key): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "'{$key}' nicht vorhanden");
            return false;
        }

        if ('' == ($row[$key])) {
            $this->setStatus($context, "'{$key}' ist leer", $row[$key]);
            return false;
        }

        if (is_null($row[$key])) {
            $this->setStatus($context, "'{$key}' ist null", $row[$key]);
            return false;
        }

        return true;
    }

    /**
     * Checks if given element has an unique key
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @param string $key
     * @return bool
     */
    private function hasUniqueValue(array $row, string $context, Collection $existing, string $key): bool
    {
        if ($existing->filter(fn (mixed $item): bool => $item[$key] ==  $row[$key])->count() > 0) {
            $this->setStatus($context, "Ressource mit diesen '{$key}' existiert bereits", $row[$key]);
            return false;
        }

        return true;
    }

    /**
     * Checks if given element has valid integer
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @return bool
     */
    private function hasValidInt(array $row, string $context, string $key, bool $unsigned = true): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "{$key} nicht vorhanden");
            return false;
        }

        $value = $row[$key];

        if (is_null($value)) {
            $this->setStatus($context, "{$key} ist leer", $value);
            return false;
        }

        if (!is_int($value)) {
            $this->setStatus($context, "{$key} kein Zahl", $value);
            return false;
        }

        if ($unsigned && 0 > $value) {
            $this->setStatus($context, "{$key} ist negativ", $value);
            return false;
        }

        return true;
    }

    /**
     * Has valid relation
     *
     * @param array  $array
     * @param string $key
     * @param array $haystack
     * @param string $context
     * @param string $relation
     * @param bool $nullable
     * @return bool
     */
    private function hasValidRelation(
        array $array,
        string $key,
        array $haystack,
        string $context,
        string $relation = 'Ressource',
        bool $nullable = false
    ): bool {
        if (!array_key_exists($key, $array)) {
            $this->setStatus("{$context}: {$relation}", "{$key} nicht vorhanden");
            return false;
        }

        if ($nullable && is_null($array[$key])) {
            return true;
        }

        if (array_key_exists($array[$key], $haystack)) {
            return true;
        }

        $this->setStatus($context, "Relation fuer {$relation} existiert nicht.", $array[$key]);
        return false;
    }


    /**
     * Check if note is valid
     *
     * @param arrat $noten
     * @param string|null $note
     * @param string $context
     * @return bool
     */
    public function hasValidNote(array $noten, string|null $note, string $context): bool
    {
        if (is_null($note)) {
            return true;
        }

        if (!in_array($note, $noten)) {
            $this->setStatus($context, "Note existiert nicht", $note);
            return false;
        }

        return true;
    }




    /// new

    /**
     * Check if the value is valid
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @param bool $nullable
     * @param bool $emptable
     * @param bool $expectedInteger
     * @return bool
     */
    private function isValidValue(
        array $row,
        string $key,
        string $context,
        bool $nullable = false,
        bool $emptable = false,
        bool $expectedInteger = false,
    ): bool {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "'{$key}' nicht vorhanden");
            return false;
        }

        if (!$nullable && $row[$key] === null) {
            $this->setStatus($context, "'{$key}' ist NULL", $row[$key]);
            return false;
        }

        if (!$emptable && $row[$key] === '') {
            $this->setStatus($context, "'{$key}' ist leer", $row[$key]);
            return false;
        }

        if ($expectedInteger && filter_var($row[$key], FILTER_VALIDATE_INT) === false) {
            $this->setStatus($context, "'{$key}' ist kein Zahl", $row[$key]);
            return false;
        }

        return true;
    }

    /**
     * Check if the array is valid
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @param bool $nullable
     * @param bool $emptable
     * @return bool
     */
    private function isValidArray(
        array $row,
        string $key,
        string $context,
        bool $nullable = false,
        bool $emptable = false,
    ): bool {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "'{$key}' nicht vorhanden");
            return false;
        }

        if (!$nullable && $row[$key] === null) {
            $this->setStatus($context, "'{$key}' ist NULL", $row[$key]);
            return false;
        }

        if (!$emptable && empty($row[$key])) {
            $this->setStatus($context, "'{$key}' ist leer", $row[$key]);
            return false;
        }

        return true;
    }

    /**
     * Checks if given element has an unique key
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @param string $key
     * @return bool
     */
    private function isUnique(array $row, Collection $existing, string $key, string $context): bool
    {
        return $existing->filter(fn (mixed $item): bool => $item[$key] == $row[$key])->count() == 0;
    }

    /**
     * Has relation
     *
     * @param array $row
     * @param string $key
     * @param Collection $collection
     * @param string $context
     * @param string $relation
     * @return bool
     */
    private function hasRelation(
        array $row,
        string $key,
        Collection $collection,
        string $context,
        string $relation = 'Ressource',
        bool $nullable = false,
        bool $emptable = false,
    ): bool {

        if ($nullable && $row[$key] === null) {
            return true;
        }

        if ($emptable && $row[$key] === '') {
            return true;
        }

        if ($collection->contains($row[$key])) {
            return true;
        }

        $this->setStatus($context, "Relation fuer {$relation} existiert nicht.", $row[$key]);
        return false;
    }

    /**
     * Checks if given element has valid id
     *
     * @param array $row
     * @param string $key
     * @param Collection $existing
     * @param string $context
     * @param bool $unique
     * @return bool
     */
    private function validId(
        array $row,
        string $key = 'id',
        Collection $existing,
        string $context,
        bool $unique = true,
    ): bool {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "'{$key}' nicht vorhanden");
            return false;
        }

        $id = $row[$key];

        if (!is_int($id)) {
            $this->setStatus($context, "'{$key}' ist keine Zahl", $id);
            return false;
        }

        if ($id <= 0) {
            $this->setStatus($context, "'{$key}' ist nicht positiv", $id);
            return false;
        }

        if ($unique && $existing->contains($id)) {
            $this->setStatus($context, "Ressource mit diesen '{$key}' existiert bereits", $id);
            return false;
        }

        return true;
    }
}
