<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Leistung, Note};
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defining the Noten controller
 */
class Noten extends Controller
{
    /**
     * @param Leistung $leistung
     * @return JsonResponse
     */
    public function __invoke(Leistung $leistung, string|null $type = 'note'): JsonResponse
	{
        // Check if the class Klasse of the Schueler related to the Leistung is allowed to have editable Noten.
        abort_unless($leistung->schueler->klasse->editable_noten, Response::HTTP_FORBIDDEN);

        // If the requested note is an empty string, call updateNote method without a specific note.
        if (request()->note == '') {
			return $this->updateNote($leistung, $type);
		}

        // Retrieve the Note model based on the requested note's 'kuerzel'.
        $note = Note::query()
            ->where('kuerzel', '=', (string) request()->note)
            ->firstOrFail();

        return $this->updateNote($leistung, $type, $note->id);
    }

    /**
     * Update the note
     *
     * @param Leistung $leistung
     * @param string $type
     * @param Note|null $note
     * @return JsonResponse
     */
    private function updateNote(Leistung $leistung, string $type, string|null $note = null)
    //: JsonResponse
    {
        // Check if type is correct
        $keys = $this->getKeys($type);
        abort_unless($keys, Response::HTTP_FORBIDDEN);

        // Updating the resource with an additional timestamp
        $leistung->update([
			$keys['id'] => $note,
			$keys['ts'] => now()->format('Y-m-d H:i:s.u'),
		]);

        // Returning a JSON response with a 204 No Content status.
		return response()->json(Response::HTTP_NO_CONTENT);
        //return "updated";
    }

    /**
     * Check if type is correct and retrive correct ones
     *
     * @param  $type
     * @return
     */
    private function getKeys(string $type): array|null
    {
        return match ($type) {
            'note' => [
                'id' => 'note_id',
                'ts' => 'tsNote',
            ],
            'note_quartal' => [
                'id' => 'note_quartal_id',
                'ts' => 'tsNoteQuartal',
            ],
            default => null,
        };
    }
}
