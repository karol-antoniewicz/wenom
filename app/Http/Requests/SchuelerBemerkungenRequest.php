<?php

namespace App\Http\Requests;

use App\Models\Bemerkung;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * A FormRequest class to handle validation and authorization for requests related current resource.
 */
class SchuelerBemerkungenRequest extends FormRequest
{
    /**
     * Determine if the given ability should be granted for the current user.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Authorization passes if current user is an administrator
		if (auth()->user()->is_administrator) {
			return true;
		}

        // Authorization passes if current user is an administrator shares klassen with current schueler
		return in_array(
			$this->schueler->klasse_id,
			auth()->user()->klassen()->pluck('id')->toArray()
		);
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'key' => Rule::in(values: Bemerkung::ALLOWED_BEMERKUNGEN),
			'value' => [
				'nullable', 'string'
			],
        ];
    }
}
