<?php

namespace App\Http\Controllers;

use App\Http\Resources\Export\SchuelerResource;
use App\Models\Schueler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

// Todo #239
class ExportController extends Controller
{
	public function __invoke(): JsonResponse
	{
		$schueler = Schueler::query()
			->with(relations: [
				'bemerkung',
				'leistungen' => ['note'],
				'lernabschnitt' => [
					'lernbereich1Note', 'lernbereich2Note', 'foerderschwerpunkt1Relation', 'foerderschwerpunkt2Relation',
				],
			])
			->get();

		return response()->json(data: SchuelerResource::collection(resource: $schueler));
	}
}
