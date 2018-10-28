<?php

namespace OzanAkman\Multilingual\Commands;

use OzanAkman\Multilingual\Models\Locale;

class SetDefault extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multilingual:set-default {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets a locale to default';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $code = $this->argument('code');
        $locale = $this->checkIfCodeExists($code);

        Locale::where('default', true)->update(['default' => false]);

        $locale->default = true;
        $locale->save();

        $this->info("Locale {$code} {$locale->name} set as default successfully!");
    }
}
