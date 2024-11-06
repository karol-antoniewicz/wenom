<?php

namespace Database\Factories;

use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Schueler model instances.
 *
 * @package Database\Factories
 */
class SchuelerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Schueler::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'jahrgang_id' => Jahrgang::factory(),
            'klasse_id' => Klasse::factory(),
            'nachname' => $this->faker->lastName(),
            'vorname' => $this->faker->firstName(),
            'geschlecht' => $this->faker->randomElement(array: Schueler::GENDERS),
        ];
    }

    /**
     * Indicate that the model has AUE.
     *
     * @return SchuelerFactory
     */
    public function bilingualeSprache(): SchuelerFactory
    {
        return $this->state(fn (): array  => [
			'bilingualeSprache' => $this->faker->unique->word(),
		]);
    }

    /**
     * Indicate that the model has Ist Zieldifferent.
     *
     * @return SchuelerFactory
     */
    public function withIstZieldifferent(): SchuelerFactory
    {
        return $this->state(fn (): array  => [
			'istZieldifferent' => true,
		]);
    }

    /**
     * Indicate that the model has Ist DaZ Foerderung.
     *
     * @return SchuelerFactory
     */
    public function withIstDaZFoerderung(): SchuelerFactory
    {
        return $this->state(fn (): array  => [
			'istDaZFoerderung' => true,
		]);
    }
}
