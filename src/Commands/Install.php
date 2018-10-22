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
        $this->alert('Starting to initialize Multilingual!');
        $this->publish();
        $this->migrate();
        $this->insertLanguages();
        $this->alert('Multilingual successfully installed!');
        $this->comment('Now, add \OzanAkman\Multilingual\Middleware\Localize::class to your Kernel!');
    }

    private function publish()
    {
        $this->info('Publishing config and migrations...');
        $this->call('vendor:publish', [
            '--provider' => 'OzanAkman\Multilingual\Providers\MultilingualServiceProvider',
        ]);
    }

    private function migrate()
    {
        $this->info('Running migrations...');
        $this->call('migrate', [
            '--path' => '/database/migrations/multilingual/'
        ]);
    }

    private function insertLanguages()
    {
        $this->info('Inserting a dummy language...');
        $locale = new Locale();
        $locale->code = 'en';
        $locale->name = 'English';
        $locale->native_name = 'English';
        $locale->default = true;
        $locale->enabled = true;
        $locale->save();
    }
}
