<?php

namespace OzanAkman\Multilingual\Commands;

use OzanAkman\Multilingual\Models\Locale;
use OzanAkman\Multilingual\Commands\Enums\Code;

class Add extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multilingual:add {code} {name} {native_name} {--enabled=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a new locale.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $args = $this->arguments();

        $this->checkIfCodeExists($args['code'], Code::CODE_EXISTS);

        $this->addLocale($args);
    }

    private function addLocale($args)
    {
        $locale = new Locale();
        $locale->code = $args['code'];
        $locale->name = $args['name'];
        $locale->native_name = $args['native_name'];
        $locale->enabled = (bool)$this->option('enabled');
        $locale->save();

        $this->info("Locale {$locale->code} {$locale->name} added successfully!");
    }
}
