<?php

namespace Database\Factories;

use App\Models\Daten;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Daten model instances.
 *
 * @package Database\Factories
 * @extends Illuminate\Database\Eloquent\Factories\Factory<Daten>
 */
class DatenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Daten::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'enmRevision' => rand(1, 10),
            'schuljahr' => rand(2000, now()->year),
            'anzahlAbschnitte' => rand(1, 4),
            'aktuellerAbschnitt' => rand(1, 4),
            'lehrerID' => $this->faker->numberBetween(1, 1_000_000),
        ];
    }

    /**
     * Indicate that the model has Public Key.
     *
     * @return DatenFactory
     */
    public function withPublicKey(): DatenFactory
    {
        return $this->state(fn (): array  => [
			'publicKey' => $this->faker->word,
		]);
    }

    /**
     * Indicate that the model has Fehlstunden Eingabe.
     *
     * @return DatenFactory
     */
    public function withFehlstundenEingabe(): DatenFactory
    {
        return $this->state(fn (): array  => [
			'fehlstundenEingabe' => true,
		]);
    }

    /**
     * Indicate that the model has Fehlstunden SI Fachbezogen.
     *
     * @return DatenFactory
     */
    public function withFehlstundenSIFachbezogen(): DatenFactory
    {
        return $this->state(fn (): array  => [
			'fehlstundenSIFachbezogen' => true,
		]);
    }

    /**
     * Indicate that the model has Fehlstunden SII Fachbezogen.
     *
     * @return DatenFactory
     */
    public function withFehlstundenSIIFachbezogen(): DatenFactory
    {
        return $this->state(fn (): array  => [
			'fehlstundenSIIFachbezogen' => true,
		]);
    }

    /**
     * Indicate that the model has Schulform.
     *
     * @return DatenFactory
     */
    public function withSchulform(): DatenFactory
    {
        return $this->state(fn (): array  => [
			'schulform' => $this->faker->word(),
		]);
    }

    /**
     * Indicate that the model has Mailadresse.
     *
     * @return DatenFactory
     */
    public function withMailadresse(): DatenFactory
    {
        return $this->state(fn (): array  => [
			'mailadresse' => $this->faker->safeEmail(),
		]);
    }
}
