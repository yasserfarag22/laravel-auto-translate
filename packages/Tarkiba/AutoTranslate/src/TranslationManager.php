<?php

namespace Tarkiba\AutoTranslate;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class TranslationManager
{
    protected $locales;
    protected $apiKey;
    protected $service;

    public function __construct()
    {
        $this->locales = config('auto-translate.supported_locales', ['en']);
        $this->apiKey = config('auto-translate.api_key');
        $this->service = config('auto-translate.translation_service', 'google');
    }

    public function translate($key, $defaultText = '')
    {
        $localePath = lang_path(config('app.locale') . '.json');
        $translations = File::exists($localePath) ? json_decode(File::get($localePath), true) : [];

        if (!isset($translations[$key])) {
            $translations[$key] = $defaultText ?: $key;
            File::put($localePath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            foreach ($this->locales as $locale) {
                if ($locale !== 'en') {
                    $this->autoTranslate($key, $defaultText ?: $key, $locale);
                }
            }
        }

        return __($key);
    }

    protected function autoTranslate($key, $text, $locale)
    {
        if (!$this->apiKey) {
            return;
        }

        $translatedText = $this->fetchTranslation($text, $locale);
        $localePath = lang_path("$locale.json");
        $translations = File::exists($localePath) ? json_decode(File::get($localePath), true) : [];
        $translations[$key] = $translatedText;
        File::put($localePath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    protected function fetchTranslation($text, $locale)
    {
        if ($this->service === 'google') {
            $response = Http::get("https://translation.googleapis.com/language/translate/v2", [
                'q' => $text,
                'target' => $locale,
                'source' => 'en',
                'key' => $this->apiKey,
            ]);

            return $response->json()['data']['translations'][0]['translatedText'] ?? $text;
        }

        return $text;
    }
}
