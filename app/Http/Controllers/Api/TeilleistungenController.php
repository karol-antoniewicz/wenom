<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Lerngruppe, Note, Teilleistung, Klasse, Leistung, Teilleistungsart};
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\{Collection, Str};
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeilleistungenController extends Controller
{
    /**
     * Initial page data
     *
     * @return JsonResponse
     */
    public function index(string $filteredTeilleistungen): JsonResponse
    {
        $selected = "";
        //take first "Klasse" as default unless resetting filter
        if ($filteredTeilleistungen == "filteredTeilleistungen")  $selected = Klasse::first();
        $collection = $this->getTeilleistungen($selected);

        $kurse = Lerngruppe::query()
            ->whereNotNull('kursartKuerzel')
            ->distinct()
            ->pluck('kursartKuerzel', 'id')
            ->unique()
            ->values();

        $klassen = Klasse::query()
            ->distinct()
            ->get()
            ->mapWithKeys(fn (Klasse $item): array => [$item->id => $item->kuerzel]);

        //Get all notes present in the noten DB table
        $noten = Note::query()
            ->orderBy('sortierung')
            ->pluck('kuerzel')
            ->toArray();

        return response()->json([
            'filters' => [
                'selected' => $selected,
                'klassen' => $klassen,
                'kurse' => $kurse,
            ],
            'leistungen' => $this->getLeistungen($collection),
            'columns' => $this->getColumns($collection),
            'notes' => $noten
        ]);
    }

    /**
     * Get "Leistungen" for given "Klasse"
     *
     * @param Klasse $klasse
     * @return JsonResponse
     */
    public function getKlasse(string $klasseKuerzel): JsonResponse
    {
        try {
            $completeKlassen = Klasse::query()
                ->where('kuerzel', '=', $klasseKuerzel)
                ->firstOrFail();
        } catch (NotFoundHttpException $e) {
            return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
       
        $collection = $this->getTeilleistungen($completeKlassen);

        return response()->json([
            'leistungen' => $this->getLeistungen($collection),
            'columns' => $this->getColumns($collection)
        ]);
    }

    /**
     * Get "Leistungen" for given "Kurs"
     *
     * @param string $kurs
     * @return JsonResponse
     */
    public function getKurs(string $kurs): JsonResponse
    {
        $collection = $this->getTeilleistungen($kurs);

        return response()->json([
            'leistungen' => $this->getLeistungen($collection),
            'columns' => $this->getColumns($collection)
        ]);
    }

        /**
     * Get "Leistungen" for all Classes and Coursese
     *
     * @param string $all
     * @return JsonResponse
     */
    public function getAllTeilleistungen(): JsonResponse
    {
        $collection = Leistung::query()
        ->whereHas('teilleistungen')
        ->with([
            'schueler', 'note', 'teilleistungen' => [
                'note', 'teilleistungsart',
            ],
        ])
        ->whereHas(
            'lerngruppe')
        ->get();
        

        return response()->json([
            'leistungen' => $this->getLeistungen($collection),
            'columns' => $this->getColumns($collection)
        ]);
    }

    /**
     * Get "Leistungen" formatted for frontend
     *
     * @param Collection $collection
     * @return array
     */
    private function getLeistungen(Collection $collection): array
    {
        $leistungen = [];

        foreach ($collection as $leistung) {
            $base = [
                'id' => $leistung->id,
                'name' => "{$leistung->schueler->nachname}, {$leistung->schueler->vorname}",
                'fach' => $leistung->lerngruppe->fach->kuerzel,
                'kurs' => $leistung->lerngruppe->kursartKuerzel,
                'klasse' => $leistung->lerngruppe->klasse->kuerzelAnzeige,
                'note' => $leistung->note?->kuerzel,
                'quartalnote' => $leistung->quartalnote?->kuerzel,
                'editable_noten' => $leistung->schueler->klasse->editable_noten,
                'editable_teilnoten' => $leistung->schueler->klasse->editable_teilnoten
            ];

            $leistungen[] = [...$base, ...$this->mapTeilleistungen($leistung)];
        }
        
        // Sort Leistungen per klasseKuerzel (eg. 5a, 6b...)
        usort($leistungen, fn (array $a, array $b): bool => $a['klasse'] >  $b['klasse']);

        return $leistungen;
    }

    /**
     * Map "Teilleistungen" for given "Leistung"
     *
     * @param Leistung $leistung
     * @return array
     */
    private function mapTeilleistungen(Leistung $leistung): array
    {
        $array = [];

        foreach($leistung->teilleistungen as $teilleistung) {
            $key = $this->teilleistungKey($teilleistung->teilleistungsart);
            $array[$key] = [
                'id' => $teilleistung->id,
                'note' => $teilleistung->note?->kuerzel,
            ];
        }
        return $array;
    }

    /**
     * Generate key for "Teilleistungsart"
     *
     * @param Teilleistungsart $teilleistungsart
     * @return string
     */
    private function teilleistungKey(Teilleistungsart $teilleistungsart): string
    {
        $slug = Str::slug($teilleistungsart->bezeichnung, '_');

        return sprintf('teilleistung_%s', $slug);
    }

    /**
     * Update the "Note" for given "Teilleistung"
     *
     * @param Teilleistung $teilleistung
     * @param Note $note
     * @return JsonResponse
     */
    public function updateNote(Teilleistung $teilleistung, string $kuerzel): JsonResponse
    {
        //Get all notes present in the noten DB table Sílvia
        $allNotes = Note::query()
            ->orderBy('sortierung')
            ->pluck('id', 'kuerzel')
            ->toArray();

        // Attempting to note_id the specified 'kuerzel'.
        try {
			$noteId= Note::query()
				->where('kuerzel', '=', $kuerzel)
                ->pluck('id')
				->firstOrFail();
		} catch (Exception $e) {
			return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
		}

        try {
            $teilleistung->update([
                'note_id' => $noteId,
                'tsNote' => now()->format('Y-m-d H:i:s.u'),
            ]);
    
            // Returning a JSON response with a 204 No Content status.
            return response()->json(status: Response::HTTP_NO_CONTENT);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Database query error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get Teilleistungen for "Klasse" or "Kurs" depending on the type of the $item
     *
     * @param Klasse|string $item
     * @return Collection
     */
    private function getTeilleistungen(Klasse|string $item): Collection
    {
        return Leistung::query()
            ->whereHas('teilleistungen')
            ->with([
                'schueler', 'note', 'teilleistungen' => [
                    'note', 'teilleistungsart',
                ],
            ])
            ->whereHas(
                'lerngruppe',
                fn (Builder $query): Builder => $query->when(
                    $item instanceof Klasse,
                    fn (Builder $query): Builder => $query->whereBelongsTo($item),
                    fn (Builder $query): Builder => $query->where('kursartKuerzel', 'like', '%' . $item . '%'),
                )
            )
            ->get();
    }

    /**
     * Get columns to render in frontend
     *
     * @param Collection $collection
     * @return array
     */
    private function getColumns(Collection $collection): array
    {
        $array = [];

        $collection->each(function (Leistung $leistung) use (&$array): void {
            $leistung->teilleistungen
               // Filter out already existing "Teilleistungsarten"
                ->filter(
                    fn (Teilleistung $teilleistung): bool =>
                    !in_array($teilleistung->teilleistungsart->id, array_column($array, 'id'))
                )
                // Iterate all Teilleistungen and map the array
                ->each(function (Teilleistung $teilleistung) use (&$array): void {
                    $art = $teilleistung->teilleistungsart;
                    $array[] = [
                        'id' => $art->id,
                        'key' => $this->teilleistungKey($art),
                        'label' => $art->bezeichnung,
                        'sortable' => true,
                        'sortierung' => $art->sortierung,
                        'toggle' => true,
                    ];
                });
        })->toArray();

        // Sort the array
        usort($array, fn (array $a, array $b): bool => $a['sortierung'] >  $b['sortierung']);

        return $array;
    }
}
