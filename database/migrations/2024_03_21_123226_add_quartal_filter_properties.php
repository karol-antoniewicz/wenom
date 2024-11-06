<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add(property: 'filter.mein_unterricht_quartalnoten', value: true);
        $this->migrator->add(property: 'filter.leistungdatenuebersicht_quartalnoten', value: true);
    }
};
