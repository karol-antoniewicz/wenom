<?php

namespace App\Http\Requests\Fehlstunden;

use App\Rules\GreaterThanOrEqualWhenPresent;
use App\Settings\MatrixSettings;
use Illuminate\Foundation\Http\FormRequest;

/**
 * A FormRequest class to handle validation and authorization for requests related current resource.
 */
class FsRequest extends FormRequest
{
    /**
     * Determine if the given ability should be granted for the current user.
     *
     * @param MatrixSettings $settings
     * @return bool
     */
	public function authorize(MatrixSettings $settings): bool
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
			'value' => new GreaterThanOrEqualWhenPresent($this->leistung->fehlstundenUnentschuldigtFach),
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
			'value' => 'Fehlstunden Fach',
		];
	}
}
