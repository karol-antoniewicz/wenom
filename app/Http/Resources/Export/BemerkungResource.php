<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `BemerkungResource` class is a JSON resource for formatting and presenting 'Bemerkung' data.
 *
 * @package App\Http\Resources\Export
 */
class BemerkungResource extends JsonResource
{
    /**
     * Transform the data into a JSON array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'ASV' => $this->ASV ? $this->formatBemerkung($this->ASV) : null,
            'tsASV' => $this->tsASV,
            'AUE' => $this->AUE ? $this->formatBemerkung($this->AUE) : null,
            'tsAUE' => $this->tsAUE,
            'ZB' => $this->ZB ? $this->formatBemerkung($this->ZB) : null,
            'tsZB' => $this->tsZB,
            'LELS' => $this->LELS,
            'schulformEmpf' => $this->schulformEmpf,
            'individuelleVersetzungsbemerkungen' => $this->individuelleVersetzungsbemerkungen
				? $this->formatBemerkung($this->individuelleVersetzungsbemerkungen)
				: null,
            'tsIndividuelleVersetzungsbemerkungen' => $this->tsIndividuelleVersetzungsbemerkungen,
            'foerderbemerkungen' => $this->foerderbemerkungen,
        ];
    }

    /**
     * Format a 'Bemerkung' string by replacing placeholders with corresponding values.
     *
     * @param string $bemerkung
     * @return string
     */
    private function formatBemerkung(string $bemerkung): string
	{
		$firstOccurrence = true;
		$pattern = '/(\$vorname\$ \$nachname\$|\$vorname\$|\$nachname\$)/i';
		$text = preg_split($pattern, $bemerkung, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

		$pronouns = ['m' => 'Er', 'w' => 'Sie'];
		$pronoun = array_key_exists($this->schueler->geschlecht, $pronouns)
			? $pronouns[$this->schueler->geschlecht]
			: null;

		$initialOccurrence = [
			'$vorname$ $nachname$' => "{$this->schueler->vorname} {$this->schueler->nachname}",
			'$vorname$' => $this->schueler->vorname,
			'$nachname$' => $this->schueler->nachname,
		];

		$succeedingOccurrences = [
			'$vorname$ $nachname$' => $pronoun ?? $this->schueler->vorname,
			'$vorname$' => $pronoun ?? $this->schueler->vorname,
			'$nachname$' => null,
		];

		foreach ($text as &$item) {
			$condition = array_key_exists(
				strtolower($item),
				$array = $firstOccurrence ? $initialOccurrence : $succeedingOccurrences
			);

			if ($condition) {
				$item = $array[strtolower($item)];
				$firstOccurrence = false;
			}
		}

		return implode(' ', $text);
	}
}
