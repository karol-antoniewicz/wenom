<?php

namespace Database\Factories;

use App\Models\Fach;
use App\Models\Schueler;
use App\Models\Sprachenfolge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Sprachenfolge model instances.
 *
 * @package Database\Factories
 */
class SprachenfolgeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Sprachenfolge::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
            'sprache' => $this->faker->unique->word(), 
            'fach_id' => Fach::factory(),
            'reihenfolge' => rand(1, 10),
        ];
    }

    /**
     * Indicate that the model has Belegung Von Jahrgang.
     *
     * @return SprachenfolgeFactory
     */
    public function withBelegungVonJahrgang(): SprachenfolgeFactory
    {
        return $this->state(fn (): array  => [
			'belegungVonJahrgang' => rand(1, 10),
		]);
    }

    /**
     * Indicate that the model has Belegung Von Abschnitt.
     *
     * @return SprachenfolgeFactory
     */
    public function withBelegungVonAbschnitt(): SprachenfolgeFactory
    {
        return $this->state(fn (): array  => [
			'belegungVonAbschnitt' => rand(1, 10),
		]);
    }

    /**
     * Indicate that the model has Belegung Bis Jahrgang.
     *
     * @return SprachenfolgeFactory
     */
    public function withBelegungBisJahrgang(): SprachenfolgeFactory
    {
        return $this->state(fn (): array  => [
			'belegungBisJahrgang' => rand(1, 10),
		]);
    }

    /**
     * Indicate that the model has BelegungBis Abschnitt.
     *
     * @return SprachenfolgeFactory
     */
    public function withBelegungBisAbschnitt(): SprachenfolgeFactory
    {
        return $this->state(fn (): array  => [
			'belegungBisAbschnitt' => rand(1, 10),
		]);
    }

    /**
     * Indicate that the model has Referenzniveau.
     *
     * @return SprachenfolgeFactory
     */
    public function withReferenzniveau(): SprachenfolgeFactory
    {
        return $this->state(fn (): array  => [
			'referenzniveau' => $this->faker->unique->word()
		]);
    }

    /**
     * Indicate that the model has Belegung SekI.
     *
     * @return SprachenfolgeFactory
     */
    public function withBelegungSekI(): Factory
    {
        return $this->state(fn (): array  => [
			'belegungSekI' => rand(3) * 2,
		]);
    }
}
