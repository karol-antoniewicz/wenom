<?php

namespace Database\Seeders;

use App\Services\DataImportService;
use File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;

// TODO: To be removed, temporary testing route #239 by Karol
class JsonImportSeeder extends Seeder
{
	private string $path = 'database/seeders/data';

    /**
     * Run the seeder.
     *
     * @return void
     * @throws FileNotFoundException
     */
	public function run(): void
	{
		$json = File::get("{$this->path}/ENMGesamt.json");

		$service = new DataImportService(
			json_decode($json, true)
		);

		$service->execute();
	}
}
