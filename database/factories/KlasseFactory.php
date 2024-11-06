<?php

namespace Database\Factories;

use App\Models\Klasse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Klasse model instances.
 *
 * @package Database\Factories
 */
class KlasseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Klasse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique()->word(),
            'kuerzelAnzeige' => $this->faker->unique()->word(),
            'sortierung' => rand(min: 1, max: 15)
        ];
    }
}
