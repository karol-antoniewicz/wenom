<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GdprSettings extends Settings
{
    // TODO: Check by Karol
	public string $domain_url;
	public string $domain_owner;

	public string $creation_date;
	public string $last_update;

	public string $report_contact_person;
	public string $feedback_form_url;

    public static function group(): string
    {
        return 'Gdpr';
    }
}