<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * A FormRequest class to handle validation and authorization for requests related current resource.
 */
class FirstLoginRequest extends FormRequest
{
    /**
     * Determine if the given ability should be granted for the current user.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->guest();
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
				'required', 'string', 'email:dns,rfc', 'min:5', 'max:255',
			],
            'kuerzel' => [
				'required', 'string', 'min:2', 'max:255',
			],
            'schulnummer' => [
				'required', 'numeric',
			],
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
			'email' => 'E-Mail-Adresse',
			'kuerzel' => 'Lehrkraftkürzel',
			'schulnummer' => 'Schulnummer',
		];
	}
}
