<?php

use OzanAkman\Multilingual\Models\Locale;
use OzanAkman\Multilingual\Models\Enums\LocaleStatus;

if (!function_exists('locales')) {
    /**
     * Get locales from the database.
     * @throws Exception
     */
    function locales()
    {
        $locales = cache('locales');

        if ($locales && count($locales) > 0) {
            return $locales;
        }

        $locales = Locale::all()
            ->where('enabled', LocaleStatus::ENABLED)
            ->keyBy('code');

        cache()->forever('locales', $locales);

        return $locales;
    }
}


if (!function_exists('default_locale')) {
    /**
     * Get default locale
     * @throws Exception
     */
    function default_locale()
    {
        return cache('locales')
            ->where('default', 1)
            ->first();
    }
}
