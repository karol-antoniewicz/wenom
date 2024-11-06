<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

/**
 * A FormRequest class to handle validation and authorization for requests related current resource.
 */
class TestMailRequest extends FormRequest
{
    /**
     * Determine if the given ability should be granted for the current user.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Authorization passes if current user is an administrator
        return auth()->user()->is_administrator;
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
                'required', 'string', 'email:rfc,dns', 'min:5', 'max:255',
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
            'email' => 'Testmail Adresse',
        ];
    }
}
