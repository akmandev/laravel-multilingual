<?php

namespace OzanAkman\Multilingual\Support;

class RouterHelper
{
    /**
     * Generate attributes by the given pattern.
     * @return array
     */
    public static function mapAttributesByPattern()
    {
        $pattern = config('multilingual.pattern');
        $attributes = [];

        if ($pattern === 'domain') {
            $attributes['domain'] = '{domain}.'.env('APP_URL');
        } elseif ($pattern === 'path') {
            $attributes['prefix'] = '{lang?}';
        }

        return $attributes;
    }
}
