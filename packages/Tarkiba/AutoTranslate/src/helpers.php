<?php

use Tarkiba\AutoTranslate\TranslationManager;

if (!function_exists('__t')) {
    function __t($key, $defaultText = '')
    {
        return app('auto-translate')->translate($key, $defaultText);
    }
}
