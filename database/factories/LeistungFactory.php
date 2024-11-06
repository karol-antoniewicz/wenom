<?php

namespace Database\Factories;

use App\Models\{Leistung, Lerngruppe, Note, Schueler};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Bemerkung model instances.
 *
 * @package Database\Factories
 */
class LeistungFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var Leistung<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Leistung::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
            'lerngruppe_id' => Lerngruppe::factory(),
        ];
    }

    /**
     * Indicate that the model has Abiturfach.
     *
     * @param int|null $amount
     * @return LeistungFactory
     */
    public function withAbiturfach(int|null $amount = null): LeistungFactory
    {
        return $this->state(fn (): array  => [
			'abiturfach' => $amount ?? rand(0, 10)
		]);
    }

    /**
     * Indicate that the model has Note.
     *
     * @return LeistungFactory
     */
    public function withNote(): LeistungFactory
    {
		return $this->withTimestamp('note_id', 'tsNote', Note::Factory());
    }

    /**
     * Indicate that the model has Quartalnote.
     *
     * @return LeistungFactory
     */
    public function withNoteQuartal(): LeistungFactory
    {
		return $this->withTimestamp('note_quartal_id', 'tsNoteQuartal', Note::Factory());
    }

    /**
     * Indicate that the model has Ist Schriftlich.
     *
     * @return LeistungFactory
     */
    public function withIstSchriftlich(): LeistungFactory
    {
        return $this->state(fn (): array  => [
			'istSchriftlich' => true,
		]);
    }

    /**
     * Indicate that the model has Fehlstunden Fach.
     *
     * @param int|null $amount
     * @return LeistungFactory
     */
    public function withFehlstundenFach(int|null $amount = null): LeistungFactory
    {
		return $this->withTimestamp('fehlstundenFach', $amount ?? rand(10));
    }

    /**
     * Indicate that the model has Fehlstunden Unentschuldigt Fach.
     *
     * @param int|null $amount
     * @return LeistungFactory
     */
    public function withFehlstundenUnentschuldigtFach(int|null $amount = null): LeistungFactory
    {
		return $this->withTimestamp('fehlstundenUnentschuldigtFach', $amount ?? rand(10));
    }

    /**
     * Indicate that the model has Fachbezogene Bemerkungen.
     *
     * @return LeistungFactory
     */
    public function withFachbezogeneBemerkungen(): LeistungFactory
    {
		return $this->withTimestamp('fachbezogeneBemerkungen');
    }

    /**
     * Indicate that the model has Ist Gemahnt.
     *
     * @return LeistungFactory
     */
	public function withIstGemahnt(): LeistungFactory
	{
		return $this->withTimestamp('istGemahnt', true);
	}

    /**
     * Indicate that the model has Neue Zuweisung Kursart.
     *
     * @return LeistungFactory
     */
	public function withNeueZuweisungKursart(): LeistungFactory
	{
		return $this->state(fn (): array  => [
			'neueZuweisungKursart' => $this->faker->word(),
		]);
	}

    /**
     * Generate a timestamped value for a given column or its timestamp column.
     *
     * @param string $column
     * @param string|null $tsColumn
     * @param string|bool|int|null $value
     * @return LeistungFactory
     */
	private function withTimestamp(
		string $column,
		string|null $tsColumn = null,
		string|bool|int|NoteFactory|null $value = null
	): LeistungFactory {
		return $this->state(fn (): array  => [
			$column => $value ?? $this->faker->paragraph(),
			$tsColumn ?? "ts{$column}" => now()->format('Y-m-d H:i:s.u'),
		]);
	}
}
