<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FilterSettings extends Settings
{
    // TODO: Check by Karol
	public bool $mein_unterricht_teilleistungen;
	public bool $mein_unterricht_mahnungen;
	public bool $mein_unterricht_fehlstunden;
	public bool $mein_unterricht_bemerkungen;
	public bool $mein_unterricht_kurs;

	public bool $mein_unterricht_quartalnoten;
	public bool $mein_unterricht_note;
	public bool $mein_unterricht_fach;

	public bool $leistungdatenuebersicht_teilleistungen;
	public bool $leistungdatenuebersicht_fachlehrer;
	public bool $leistungdatenuebersicht_mahnungen;
	public bool $leistungdatenuebersicht_fehlstunden;
	public bool $leistungdatenuebersicht_bemerkungen;
	public bool $leistungdatenuebersicht_kurs;

	public bool $leistungdatenuebersicht_quartalnoten;
	public bool $leistungdatenuebersicht_note;
	public bool $leistungdatenuebersicht_fach;

	public static function group(): string
    {
        return 'filter';
    }
}