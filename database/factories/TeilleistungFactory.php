<?php

namespace Database\Factories;

use App\Models\Leistung;
use App\Models\Note;
use App\Models\Teilleistung;
use App\Models\Teilleistungsart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Teilleistung model instances.
 *
 * @package Database\Factories
 */
class TeilleistungFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Teilleistung::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'leistung_id' => Leistung::factory(),
            'teilleistungsart_id' => Teilleistungsart::factory(),
        ];
    }

    /**
     * Indicate that the model has Datum.
     *
     * @return TeilleistungFactory
     */
    public function withDatum(): TeilleistungFactory
    {
        return $this->state(fn (): array => [
			'datum' => now()->format('Y-m-d'),
		]);
    }

    /**
     * Indicate that the model has Bemerkung.
     *
     * @return TeilleistungFactory
     */
    public function withBemerkung(): TeilleistungFactory
    {
        return $this->state(fn (): array => [
			'bemerkung' => $this->faker->paragraph,
		]);
    }

    /**
     * Indicate that the model has Note.
     *
     * @return TeilleistungFactory
     */
    public function withNote(): TeilleistungFactory
    {
        return $this->state(fn (): array => [
			'note_id' => Note::factory(),
		]);
    }
}
