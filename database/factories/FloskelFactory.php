<?php

namespace Database\Factories;

use App\Models\Fach;
use App\Models\Floskel;
use App\Models\Floskelgruppe;
use App\Models\Jahrgang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Floskel model instances.
 *
 * @package Database\Factories
 */
class FloskelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Floskel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'floskelgruppe_id' => Floskelgruppe::class,
            'kuerzel' => $this->faker->word(),
            'text' => $this->faker->paragraph(),
        ];
    }

    /**
     * Indicate that the model has Fach.
     *
     * @return FloskelFactory
     */
    public function hasFach(): FloskelFactory
    {
        return $this->state(fn (): array  => [
			'fach_id' => Fach::factory(),
		]);
    }

    /**
     * Indicate that the model has Niveau.
     *
     * @return FloskelFactory
     */
    public function hasNiveau(): FloskelFactory
    {
        return $this->state(fn (): array  => [
			'niveau' => rand(1, 10),
		]);
    }

    /**
     * Indicate that the model has Jahrgang.
     *
     * @return FloskelFactory
     */
    public function hasJahrgang(): FloskelFactory
    {
        return $this->state(fn (): array  => [
			'jahrgang_id' => Jahrgang::factory(),
		]);
    }
}

