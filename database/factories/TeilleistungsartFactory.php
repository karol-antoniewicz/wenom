<?php

namespace Database\Factories;

use App\Models\Teilleistungsart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Teilleistungsart model instances.
 *
 * @package Database\Factories
 */
class TeilleistungsartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Teilleistungsart::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'bezeichnung' => $this->faker->unique->word(),
        ];
    }

    /**
     * Indicate that the model has Sortierung.
     *
     * @return TeilleistungsartFactory
     */
    public function withSortierung(): TeilleistungsartFactory
    {
        return $this->state(fn (): array => [
			'sortierung' => rand(1, 15),
		]);
    }

    /**
     * Indicate that the model has Gewichtung.
     *
     * @return TeilleistungsartFactory
     */
    public function withGewichtung(): TeilleistungsartFactory
    {
        return $this->state(fn (): array => [
			'gewichtung' => (float) rand(1, 100) / 100],
		);
    }
}