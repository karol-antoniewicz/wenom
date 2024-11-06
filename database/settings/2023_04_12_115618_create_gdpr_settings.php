<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
		$this->migrator->add(property: 'gdpr.domain_url', value: '[URL der Domain ergänzen]');
		$this->migrator->add(property: 'gdpr.domain_owner', value: '[Inhaber der Domain ergänzen]');

		$this->migrator->add(property: 'gdpr.creation_date', value: '[Datum]');
		$this->migrator->add(property: 'gdpr.last_update', value: '[Datum]');

		$this->migrator->add(property: 'gdpr.report_contact_person', value: '[Name des Ansprechpartners]');
		$this->migrator->add(property: 'gdpr.feedback_form_url', value: '');
    }
};
