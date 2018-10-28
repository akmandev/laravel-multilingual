<?php

namespace OzanAkman\Multilingual\Commands;

use OzanAkman\Multilingual\Models\Locale;
use Illuminate\Console\Command as BaseCommand;
use OzanAkman\Multilingual\Commands\Enums\Code;

class Command extends BaseCommand
{
    protected function checkIfCodeExists($code, $errorOn = Code::CODE_DOES_NOT_EXIST)
    {
        $locale = Locale::where('code', $code)->first();

        if ($errorOn === Code::CODE_DOES_NOT_EXIST) {
            if (!$locale) {
                $this->error("Locale {$code} doesn't exist!");
                exit();
            }
        }

        if ($errorOn === Code::CODE_EXISTS) {
            if ($locale) {
                $this->error("Locale {$code} {$locale->name} already exists!");
                exit();
            }
        }

        return $locale;
    }
}