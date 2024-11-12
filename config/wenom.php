<?php

return [
    'version' => '0.1.5',
    'npm' => '0.9.3',
    'revision' => -1,
    'two_factor_authentication' => env('TWO_FACTOR_AUTHENTICATION', false),
    'schulnummer' => env('SCHULNUMMER'),
    'aes_password' => env('AES_PASSWORD'),
    'aes_salt' => env('AES_SALT'),
    'mail_send_credentials' => [
        'mailer' => env('MAIL_MAILER'),
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'encryption' => env('MAIL_ENCRYPTION'),
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],
    'filters' => [
        'meinunterricht' => [
            'teilleistungen' => env('FILTERS_MEINUNTERRICHT_TEILLEISTUNGEN', false),
            'mahnungen' => env('FILTERS_MEINUNTERRICHT_MAHNUNGEN', true),
            'fehlstunden' => env('FILTERS_MEINUNTERRICHT_FEHLSTUNDEN', false),
            'bemerkungen' => env('FILTERS_MEINUNTERRICHT_BEMERKUNGEN', true),
            'kurs' => env('FILTERS_MEINUNTERRICHT_KURS', true),
            'quartalnoten' => env('FILTERS_MEINUNTERRICHT_QUARTALNOTEN', true),
            'note' => env('FILTERS_MEINUNTERRICHT_NOTE', true),
            'fach' => env('FILTERS_MEINUNTERRICHT_FACH', true),
        ],
        'leistungsdatenuebersicht' => [
            'teilleistungen' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_TEILLEISTUNGEN', false),
            'fachlehrer' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_FACHLEHRER', true),
            'mahnungen' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_MAHNUNGEN', false),
            'fehlstunden' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_FEHLSTUNDEN', false),
            'bemerkungen' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_BEMERKUNGEN', true),
            'kurs' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_KURS', true),
            'quartalnoten' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_QUARTALNOTEN', true),
            'note' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_NOTE', true),
            'fach' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_FACH', true),
        ],
    ],
];
