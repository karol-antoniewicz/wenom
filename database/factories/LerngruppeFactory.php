<?php

namespace Database\Factories;

use App\Models\Daten;
use App\Models\Fach;
use App\Models\Klasse;
use App\Models\Kurs;
use App\Models\Lerngruppe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Lerngruppe model instances.
 *
 * @package Database\Factories
 */
class LerngruppeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Lerngruppe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'fach_id' => Fach::factory(),
            'klasse_id' => Klasse::factory(),
			'kID' => $this->faker->numberBetween(1, 1_000_000),
            'kursartID' => $this->faker->numberBetween(1, 1_000_000),
            'bezeichnung' => $this->faker->unique->word(),
            'kursartKuerzel' => $this->faker->unique->word(),
			'bilingualeSprache' => $this->faker->unique->word(),
            'wochenstunden' => rand(1, 10),
        ];
    }


}
