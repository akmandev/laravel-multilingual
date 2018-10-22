<?php

namespace OzanAkman\Multilingual\Providers;

use DateTime;
use Illuminate\Support\ServiceProvider;
use OzanAkman\Multilingual\Commands\Install;
use OzanAkman\Multilingual\Models\Translation;
use OzanAkman\Multilingual\Observers\TranslationObserver;

class MultilingualServiceProvider extends ServiceProvider
{
    const PACKAGE_DIR = __DIR__ . '/../..';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Install::class,
            ]);
        }

        $this->publishesMigration('CreateLocalesTable', 'create_locales_table', 1);
        $this->publishesMigration('CreateTranslationsTable', 'create_translations_table', 2);
        $this->loadViewsFrom(self::PACKAGE_DIR . '/resource/views', 'multilingual');

        $this->publishes([
            self::PACKAGE_DIR . '/config/multilingual.php' => config_path('multilingual.php'),
            self::PACKAGE_DIR . '/resources/views' => resource_path('views/vendor/multilingual'),
        ]);

        Translation::observe(TranslationObserver::class);

        require_once self::PACKAGE_DIR . '/src/Support/helpers.php';
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::PACKAGE_DIR . '/config/multilingual.php', 'multilingual');
    }

    protected function publishesMigration(string $className, string $fileName, int $timestampSuffix)
    {
        if (!class_exists($className)) {
            $timestamp = (new DateTime())->format('Y_m_d_His') . $timestampSuffix;
            $this->publishes([
                self::PACKAGE_DIR . "/database/migrations/{$fileName}.php.stub" => database_path('migrations/multilingual/' . $timestamp . "_{$fileName}.php"),
            ], 'migrations');
        }
    }
}
