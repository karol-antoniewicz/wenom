<?php

namespace Database\Factories;

use App\Models\{Team, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * Factory for creating User model instances.
 *
 * @package Database\Factories
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
	protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
	public function definition(): array
	{
		return [
            'kuerzel' => $this->faker->unique->word(),
			'vorname' => $this->faker->firstName(),
			'nachname' => $this->faker->lastName(),
			'geschlecht' => $this->faker->randomElement(User::GENDERS),
			'email' => $this->faker->unique()->safeEmail(),
			'email_verified_at' => now(),
			'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
			'is_administrator' => false,
			'remember_token' => Str::random(10),
		];
	}

    /**
     * Indicate that the model is unverified.
     *
     * @return UserFactory
     */
	public function unverified(): UserFactory
	{
		return $this->state(fn (): array  => ['email_verified_at' => null]);
	}

    /**
     * Indicate that the model is administrator.
     *
     * @return UserFactory
     */
	public function administrator(): Factory
	{
		return $this->state(fn (): array  => ['is_administrator' => true]);
	}

    /**
     * Indicate that the model is lehrer.
     *
     * @return Factory
     */
	public function lehrer(): Factory
	{
		return $this->state(fn (): array  => ['is_administrator' => false]);
	}

    /**
     * Indicate that the model has Personal Team.
     *
     * @return Factory
     */
	public function withPersonalTeam(): Factory
	{
		if (! Features::hasTeamFeatures()) {
			return $this->state([]);
		}

		return $this->has(
			Team::factory()->state(fn (array $attributes, User $user): array => [
				'name' => $user->kuerzel.'\'s Team',
				'user_id' => $user->id,
				'personal_team' => true
			]),
			'ownedTeams'
		);
	}
}
