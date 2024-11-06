<?php

namespace Database\Factories;

use App\Models\Foerderschwerpunkt;
use App\Models\Lernabschnitt;
use App\Models\Note;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Lernabschnitt model instances.
 *
 * @package Database\Factories
 */
class LernabschnittFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Lernabschnitt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
            'pruefungsordnung' => $this->faker->unique->word(),
            'lernbereich1note' => Note::factory(),
            'lernbereich2note' => Note::factory(),
            'foerderschwerpunkt1' => Foerderschwerpunkt::factory(),
            'foerderschwerpunkt2' => Foerderschwerpunkt::factory(),
        ];
    }
}
