<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MatrixSettings extends Settings
{
    // TODO: Check by Karol
	public bool $lehrer_can_override_fachlehrer;

    public static function group(): string
    {
        return 'matrix';
    }
}