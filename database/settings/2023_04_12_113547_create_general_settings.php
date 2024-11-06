<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void // TODO: To be reworked
	{
		$this->migrator->add(property: 'general.name', value: '[Name der Schule]');
		$this->migrator->add(property: 'general.address', value: '[Adresse der Schule]');
		$this->migrator->add(property: 'general.email', value: '[E-Mail Adresse der Schule]');

		$this->migrator->add(property: 'general.management_name', value: '[Name Schulleitung]');
		$this->migrator->add(property: 'general.management_telephone', value: '[Sekretariat]');
		$this->migrator->add(property: 'general.management_email', value: '[E-Mail Adresse]');

		$this->migrator->add(property: 'general.board_name', value: '[Name des Schulträgers]');
		$this->migrator->add(property: 'general.board_address', value: '[Anschrift des Schulträgers]');
		$this->migrator->add(property: 'general.board_contact', value: '[Kontaktdaten des Schulträgers]');

		$this->migrator->add(property: 'general.gdpr_email', value: '[Email des Datenschutzbeauftragten]');
		$this->migrator->add(property: 'general.gdpr_address', value: '[Anschrift des Datenschutzbeauftragten]');

		$this->migrator->add(property: 'general.hosting_provider_name', value: '[Name des Hosters]');
		$this->migrator->add(property: 'general.hosting_provider_address', value: '[Anschrift des Hosters]');
	}
};


