<?php

namespace Database\Factories;

use App\Models\Bemerkung;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Bemerkung model instances.
 *
 * @package Database\Factories
 */
class BemerkungFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Bemerkung::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
        ];
    }

    /**
     * Indicate that the model has AUE.
     *
     * @return BemerkungFactory
     */
    public function withAUE(): BemerkungFactory
    {
		return $this->withTimestamp('AUE');
    }

    /**
     * Indicate that the model has ASV.
     *
     * @return BemerkungFactory
     */
    public function withASV(): BemerkungFactory
    {
		return $this->withTimestamp('ASV');
    }

    /**
     * Indicate that the model has ZB.
     *
     * @return BemerkungFactory
     */
    public function withZB(): BemerkungFactory
    {
		return $this->withTimestamp('ZB');
    }

    /**
     * Indicate that the model has LELS.
     *
     * @return BemerkungFactory
     */
    public function withLELS(): BemerkungFactory
    {
        return $this->state(fn (): array => [
			'LELS' => $this->faker->unique->paragraph()
		]);
    }

    /**
     * Indicate that the model has Schulform Empf.
     *
     * @return BemerkungFactory
     */
    public function withSchulformEmpf(): BemerkungFactory
    {
        return $this->state(fn (): array => [
			'schulformEmpf' => $this->faker->unique->paragraph(),
		]);
    }

    /**
     * Indicate that the model has Individuelle Versetzungsbemerkungen.
     *
     * @return BemerkungFactory
     */
    public function withIndividuelleVersetzungsbemerkungen(): BemerkungFactory
    {
		return $this->withTimestamp('individuelleVersetzungsbemerkungen');
    }

    /**
     * Indicate that the model has Foerderbemerkungen.
     *
     * @return BemerkungFactory
     */
    public function withFoerderbemerkungen(): BemerkungFactory
    {
        return $this->state(fn (): array => [
			'foerderbemerkungen' => $this->faker->unique->paragraph(),
		]);
    }

    /**
     * Generate a timestamped value for a given column or its timestamp column.
     *
     * @param string $column
     * @param string|null $tsColumn
     * @param string|bool|int|null $value
     * @return BemerkungFactory
     */
    private function withTimestamp(
		string $column,
		string|null $tsColumn = null,
		string|bool|int|null $value = null
	): BemerkungFactory {
		return $this->state(fn (): array => [
			$column => $value ?? $this->faker->paragraph(),
			$tsColumn ?? "ts{$column}" => now()->format('Y-m-d H:i:s.u'),
		]);
	}
}
