<?php

namespace App\Http\Requests\Fehlstunden;

use App\Rules\LessThanOrEqualWhenPresent;
use Illuminate\Foundation\Http\FormRequest;

/**
 * A FormRequest class to handle validation and authorization for requests related current resource.
 */
class GfsuRequest extends FormRequest
{
    /**
     * Determine if the given ability should be granted for the current user.
     *
     * @return bool
     */
	public function authorize(): bool
	{
		return true;
	}

    /**
     * Validation rules
     *
     * @return array
     */
	public function rules(): array
	{
		return [
			'value' => new LessThanOrEqualWhenPresent(
				$this->schueler->lernabschnitt->fehlstundenGesamt,
			),
		];
	}

    /**
     * Validation attributes
     *
     * @return array
     */
	public function attributes(): array
	{
		return [
			'value' => 'Fehlstunden gesamt unentschuldigt',
		];
	}
}
