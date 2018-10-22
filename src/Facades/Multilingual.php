<?php

namespace OzanAkman\Multilingual\Facades;

use Illuminate\Support\Facades\Facade;

class Multilingual extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Multilingual';
    }
}
