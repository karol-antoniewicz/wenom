<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DataImportService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as Status;

// Todo #239
class ImportController extends Controller
{


    public function curl()
	{
		$endpoint = 'https://nightly.svws-nrw.de/db/ENM1/enm/alle';

		$response = Http::accept(contentType: 'application/json')
			->withBasicAuth(username: 'Admin', password: '')
			->get(url: $endpoint);

		$this->import(
			data: json_decode(
				json: $response->body(),
				associative: true
			)
		);
	}

	public function gzipEnm()
	{
		$endpoint = 'https://nightly.svws-nrw.de/db/ENM1/enm/alle/gzip';

		$response = Http::accept(contentType: 'application/octet-stream')
			->withBasicAuth(username: 'Admin', password: '')
			->get(url: $endpoint);

		$this->import(
			data: json_decode(
				json: gzdecode($response->body()),
				associative: true
			)
		);
	}

	public function gzip(): JsonResponse
	{
		$key = 'file';

		if (!request()->has(key: $key)) {
			return response()->json(data: 'No file found.', status: Status::HTTP_UNPROCESSABLE_ENTITY);
		}

		$decodedData = gzdecode(
			data: request()->file(key: $key)->getContent()
		);

		if ($decodedData === false) {
			return response()->json(data: 'File invalid.', status: Status::HTTP_BAD_REQUEST);
		}

		$this->import(
			data: json_decode(json: $decodedData, associative: true)
		);

		return response()->json(status: Status::HTTP_OK);
	}

    public function request(): void
    {
		$keys = [
			'lehrer',
			'foerderschwerpunkte',
			'klassen',
			'noten',
			'jahrgaenge',
			'faecher',
			'floskelgruppen',
			'lerngruppen',
			'teilleistungsarten',
			'schueler'
		];

		$this->import(data: request()->only(keys: $keys));
    }

	private function import(array $data): void
	{
		$service = new DataImportService(data: $data);
		$service->import();

		User::all()->each(callback: fn (User $user): bool => $user->update(
			attributes: ['password' => Hash::make(value: 'password')])
		);
	}
}
