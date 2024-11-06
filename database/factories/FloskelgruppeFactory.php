<?php

namespace Database\Factories;

use App\Models\Floskelgruppe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Floskelgruppe model instances.
 *
 * @package Database\Factories
 */
class FloskelgruppeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Floskelgruppe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique->word(),
            'bezeichnung' => $this->faker->paragraph(),
            'hauptgruppe' => $this->faker->unique->word(),
        ];
    }
}
