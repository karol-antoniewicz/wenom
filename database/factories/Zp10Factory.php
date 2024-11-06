<?php

namespace Database\Factories;

use App\Models\Fach;
use App\Models\Note;
use App\Models\Schueler;
use App\Models\Zp10;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Zp10 model instances.
 *
 * @package Database\Factories
 */
class Zp10Factory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Zp10::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
            'fach_id' => Fach::factory(),
            'vornote' => Note::factory(),
            'noteSchriftlichePruefung' => Note::factory(),
            'noteMuendlichePruefung' => Note::factory(),
            'abschlussnote' => Note::factory(),
        ];
    }

    /**
     * Indicate that the model has Muendliche Pruefung.
     *
     * @return Zp10Factory
     */
    public function withMuendlichePruefung(): Zp10Factory
    {
        return $this->state(fn (): array => [
			'muendlichePruefung' => true,
		]);
    }

    /**
     * Indicate that the model has Muendliche Pruefung Freiwillig.
     *
     * @return Zp10Factory
     */
    public function withMuendlichePruefungFreiwillig(): Zp10Factory
    {
        return $this->state(fn (): array => [
			'muendlichePruefungFreiwillig' => true,
		]);
    }    
}
