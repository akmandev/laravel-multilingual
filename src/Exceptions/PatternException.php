<?php

namespace OzanAkman\Multilingual\Exceptions;

class PatternException extends \Exception
{
    public static function invalidPattern(string $pattern)
    {
        return new static("The pattern type {$pattern} is not supported. Supported types are: domain, path");
    }
}
