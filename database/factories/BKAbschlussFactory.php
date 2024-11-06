<?php

namespace Database\Factories;

use App\Models\BKAbschluss;
use App\Models\Note;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating BKAbschluss model instances.
 *
 * @package Database\Factories
 */
class BKAbschlussFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = BKAbschluss::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(), 
            'notePraktischePruefung' => Note::factory(),
            'noteKolloqium' => Note::factory(),
            'themaAbschlussarbeit' => $this->faker->paragraph(),
            'noteFachpraxis' => Note::factory(),
        ];
    }

    /**
     * Indicate that the model has Hat Zulassung.
     *
     * @return BKAbschlussFactory
     */
    public function withHatZulassung(): BKAbschlussFactory
    {
        return $this->state(fn (): array => [
			'hatZulassung' => true,
		]);
    }

    /**
     * Indicate that the model has Hat Bestanden.
     *
     * @return BKAbschlussFactory
     */
    public function withHatBestanden(): BKAbschlussFactory
    {
        return $this->state(fn (): array => [
			'hatBestanden' => true,
		]);
    }

    /**
     * Indicate that the model has Hat Zulassung Erweiterte BeruflicheKenntnisse.
     *
     * @return BKAbschlussFactory
     */
    public function withHatZulassungErweiterteBeruflicheKenntnisse(): BKAbschlussFactory
    {
        return $this->state(fn (): array => [
			'hatZulassungErweiterteBeruflicheKenntnisse' => true,
		]);
    }

    /**
     * Indicate that the model has Hat Erworben Erweiterte BeruflicheKenntnisse.
     *
     * @return BKAbschlussFactory
     */
    public function withHatErworbenErweiterteBeruflicheKenntnisse(): BKAbschlussFactory
    {
        return $this->state(fn (): array => [
			'hatErworbenErweiterteBeruflicheKenntnisse' => true,
		]);
    }

    /**
     * Indicate that the model has Hat Zulassung Berufsabschlusspruefung.
     *
     * @return BKAbschlussFactory
     */
    public function withHatZulassungBerufsabschlusspruefung(): BKAbschlussFactory
    {
        return $this->state(fn (): array => [
			'hatZulassungBerufsabschlusspruefung' => true,
		]);
    }

    /**
     * Indicate that the model has Hat Bestanden Berufsabschlusspruefung.
     *
     * @return BKAbschlussFactory
     */
    public function withHatBestandenBerufsabschlusspruefung(): BKAbschlussFactory
    {
        return $this->state(fn (): array => [
			'hatBestandenBerufsabschlusspruefung' => true,
		]);
    }

    /**
     * Indicate that the model has Ist Vorhanden Berufsabschlusspruefung.
     *
     * @return BKAbschlussFactory
     */
    public function withIstVorhandenBerufsabschlusspruefung(): BKAbschlussFactory
    {
        return $this->state(fn (): array => [
			'istVorhandenBerufsabschlusspruefung' => true,
		]);
    }

    /**
     * Indicate that the model has Ist Fachpraktischer TeilAusreichend.
     *
     * @return BKAbschlussFactory
     */
    public function withIstFachpraktischerTeilAusreichend(): BKAbschlussFactory
    {
        return $this->state(fn (): array => [
			'istFachpraktischerTeilAusreichend' => true,
		]);
    }
}
