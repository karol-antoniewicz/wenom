<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * A FormRequest class to handle validation and authorization for requests related current resource.
 */
class SecureImportRequest extends FormRequest
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
            'file' => [
                'required', 'file'
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
            'file' => 'Datei',
        ];
    }
}
