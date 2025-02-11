<?php

return [
    'default_locale' => 'en',
    'supported_locales' => ['en', 'ar', 'fr', 'es'],
    'translation_service' => 'google', // google | deepl | custom
    'api_key' => env('TRANSLATION_API_KEY', ''),
];
