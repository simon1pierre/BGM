<?php

return [
    // rw is ministry-primary language.
    'primary_locale' => env('TRANSLATION_PRIMARY_LOCALE', 'rw'),
    'supported_locales' => ['rw', 'en', 'fr'],

    // When true, missing locales are auto-filled from available source locale.
    'auto_fill_enabled' => env('TRANSLATION_AUTO_FILL_ENABLED', true),

    // Provider: libretranslate|null
    'provider' => env('TRANSLATION_PROVIDER', 'null'),
    'providers' => [
        'libretranslate' => [
            'url' => env('LIBRETRANSLATE_URL'),
            'api_key' => env('LIBRETRANSLATE_API_KEY'),
            'timeout' => (int) env('LIBRETRANSLATE_TIMEOUT', 15),
        ],
    ],

    // Scores are 0.0 - 1.0
    'quality_thresholds' => [
        'rw' => 0.90,
        'en' => 0.85,
        'fr' => 0.85,
    ],

    // Domain glossary to force consistent faith terms.
    'glossary' => [
        'rw' => [
            'en' => [
                'ubutumwa bwiza' => 'gospel',
                'ubuntu' => 'grace',
                'kwizera' => 'faith',
                'umusaraba' => 'cross',
                'Yesu Kristo' => 'Jesus Christ',
            ],
            'fr' => [
                'ubutumwa bwiza' => 'evangile',
                'ubuntu' => 'grace',
                'kwizera' => 'foi',
                'umusaraba' => 'croix',
                'Yesu Kristo' => 'Jesus-Christ',
            ],
        ],
    ],
];



