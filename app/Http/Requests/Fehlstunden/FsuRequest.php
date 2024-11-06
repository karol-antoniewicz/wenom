<?php

namespace App\Http\Requests\Fehlstunden;

use App\Rules\LessThanOrEqualWhenPresent;
use App\Settings\MatrixSettings;
use Illuminate\Foundation\Http\FormRequest;

/**
 * A FormRequest class to handle validation and authorization for requests related current resource.
 */
class FsuRequest extends FormRequest
{
    /**
     * Determine if the given ability should be granted for the current user.
     *
     * @param MatrixSettings $settings
     * @return bool
     */
	public function authorize(MatrixSettings $settings): bool
	{
		return true; // Todo: check
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
				$this->leistung->fehlstundenFach,
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
			'value' => 'Fehlstunden unentschuldigt Fach',
		];
	}
}
