<?php

namespace Database\Factories;

use App\Models\Daten;
use App\Models\Foerderschwerpunkt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Foerderschwerpunkt model instances.
 *
 * @package Database\Factories
 */
class FoerderschwerpunktFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Foerderschwerpunkt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique->word(),
            'beschreibung' => $this->faker->paragraph(),
            'sortierung' => $this->faker->unique()->numberBetween(1, 1_000_000),
        ];
    }
}
