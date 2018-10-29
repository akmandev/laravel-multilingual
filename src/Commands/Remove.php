<?php

namespace OzanAkman\Multilingual\Commands;

use OzanAkman\Multilingual\Commands\Enums\Code;

class Remove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multilingual:remove {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes a locale';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $code = $this->argument('code');

        $locale = $this->checkIfCodeExists($code);

        if ($locale->default === true) {
            $this->error("Locale {$code} is the default locale! You can't remove the default locale.");
            $this->error('Please, change the default locale.');
            exit();
        }

        $this->info("Locale {$locale->code} {$locale->name} deleted successfully!");
        $locale->delete();
        $this->invalidateCache();
    }
}
