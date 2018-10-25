<?php

namespace OzanAkman\Multilingual\Commands;

use Illuminate\Console\Command;
use OzanAkman\Multilingual\Models\Locale;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multilingual:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setups localization resources for the app.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->publish();
        $this->migrate();
        $this->insertLanguages();
        $this->info('Multilingual successfully installed!');
        $this->info('Now, add \OzanAkman\Multilingual\Middleware\Localize::class to Kernel!');
    }

    private function publish()
    {
        $this->comment('Publishing config and migrations...');
        $this->callSilent('vendor:publish', [
            '--provider' => 'OzanAkman\Multilingual\Providers\MultilingualServiceProvider',
        ]);
    }

    private function migrate()
    {
        $this->comment('Running migrations...');
        $this->callSilent('migrate', [
            '--path' => '/database/migrations/multilingual/'
        ]);
    }

    private function insertLanguages()
    {
        $this->comment('Inserting the first language: English, en...');
        $locale = new Locale();
        $locale->code = 'en';
        $locale->name = 'English';
        $locale->native_name = 'English';
        $locale->default = true;
        $locale->enabled = true;
        $locale->save();
    }
}
