<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * A custom validation rule that checks if the given value is less than
 * or equal to a specified value when that value is present.
 */
class LessThanOrEqualWhenPresent implements Rule
{
    /**
     * Class constructor
     *
     * @param int|null $right
     */
	public function __construct(private int|null $right = null) {}

    /**
     * Validates that the given value is less than or equal to the specified value when it is present.
     *
     * @param $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, mixed $value): bool
	{
        // Check if the value is an integer
		if (!is_int((int) $value)) {
			return false;
		}

        // Ensure the value is not negative
		if ($value < 0) {
			return false;
		}

        // If the value is null or an empty string, the validation passes
        if (in_array($value, [null, ''])) {
			return true;
		}

        // Compare the value with the right value (default to 0 if right value is null)
        return $value <= ($this->right ?? 0);
	}

    /**
     * The validation error message to be used when validation fails.
     *
     * @return string
     */
	public function message(): string
	{
		return 'The validation error message.';
	}
}
