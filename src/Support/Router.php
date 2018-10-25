<?php

namespace OzanAkman\Multilingual\Support;

class Router
{
    /**
     * Extend the default router via localized method.
     */
    public function locale()
    {
        return function () {
            $args = func_get_args();

            if (is_array($args[0])) {
                $attributes = [0];
                $routes = $args[1];
            } else {
                $attributes = [];
                $routes = $args[0];
            }

            $attributes = array_merge($attributes, Router::mapAttributesByPattern());
            $this->group($attributes, $routes);
        };
    }

    /**
     * Generate attributes by the given pattern.
     * @return array
     */
    public static function mapAttributesByPattern()
    {
        $pattern = config('multilingual.pattern');
        $attributes = [];

        if ($pattern === 'domain') {
            $attributes['domain'] = '{domain}.' . env('APP_URL');
        } else if ($pattern === 'path') {
            $attributes['prefix'] = '{lang?}';
        }

        return $attributes;
    }
}
