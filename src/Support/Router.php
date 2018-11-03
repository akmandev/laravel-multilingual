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

            $attributes = array_merge($attributes, RouterHelper::mapAttributesByPattern());

            $this->group($attributes, $routes);
        };
    }
}
