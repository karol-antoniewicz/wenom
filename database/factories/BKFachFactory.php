<?php

namespace Database\Factories;

use App\Models\BKAbschluss;
use App\Models\BKFach;
use App\Models\Fach;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating BKFach model instances.
 *
 * @package Database\Factories
 */
class BKFachFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = BKFach::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'bkabschluss_id' => BKAbschluss::factory(),
            'fach_id' => Fach::factory(),
            'user_id' => User::factory(),
            'vornote' => Note::factory(),
            'noteSchriftlichePruefung' => Note::factory(),        
            'noteMuendlichePruefung' => Note::factory(),
            'noteBerufsabschluss' => Note::factory(),
            'abschlussnote' => Note::factory(),
        ];
    }

    /**
     * Indicate that the model has Ist Schriftlich.
     *
     * @return BKFachFactory
     */
    public function withIstSchriftlich(): BKFachFactory
    {
        return $this->state(fn (): array => [
			'istSchriftlich' => true,
		]);
    }

    /**
     * Indicate that the model has Muendliche Pruefung.
     *
     * @return BKFachFactory
     */
    public function withMuendlichePruefung(): BKFachFactory
    {
        return $this->state(fn (): array => [
			'muendlichePruefung' => true,
		]);
    }

    /**
     * Indicate that the model has Muendliche Pruefung Freiwillig.
     *
     * @return BKFachFactory
     */
    public function withMuendlichePruefungFreiwillig(): BKFachFactory
    {
        return $this->state(fn (): array  => [
			'muendlichePruefungFreiwillig' => true,
		]);
    }

    /**
     * Indicate that the model has Ist Schriftlich Berufsabschluss.
     *
     * @return BKFachFactory
     */
    public function withIstSchriftlichBerufsabschluss(): BKFachFactory
    {
        return $this->state(fn (): array  => [
			'istSchriftlichBerufsabschluss' => true,
		]);
    }
}
