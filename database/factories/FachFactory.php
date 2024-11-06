<?php

namespace Database\Factories;

use App\Models\Fach;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Fach model instances.
 *
 * @package Database\Factories
 */
class FachFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Fach::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique->word(),
            'kuerzelAnzeige' => $this->faker->unique->word(),
            'sortierung' => rand(min: 1, max: 15),
            'istFremdsprache' => $this->faker->boolean(),
        ];
    }
}