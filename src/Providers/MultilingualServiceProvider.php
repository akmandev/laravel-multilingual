<?php

namespace OzanAkman\Multilingual\Providers;

use DateTime;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use OzanAkman\Multilingual\Commands\Add;
use OzanAkman\Multilingual\Support\Router;
use OzanAkman\Multilingual\Commands\Remove;
use OzanAkman\Multilingual\Commands\Install;
use OzanAkman\Multilingual\Models\Translation;
use OzanAkman\Multilingual\Commands\SetDefault;
use OzanAkman\Multilingual\Observers\TranslationObserver;

class MultilingualServiceProvider extends ServiceProvider
{
    const PACKAGE_DIR = __DIR__ . '/../..';

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Install::class,
                Add::class,
                Remove::class,
                SetDefault::class,
            ]);
        }

        $this->publishesMigration('CreateLocalesTable', 'create_locales_table', 1);
        $this->publishesMigration('CreateTranslationsTable', 'create_translations_table', 2);
        $this->publishes([
            self::PACKAGE_DIR . '/config/multilingual.php' => config_path('multilingual.php'),
        ]);
        $this->bladeDirectives();
        $this->translationObserver();

        require_once self::PACKAGE_DIR . '/src/Support/helpers.php';
    }

    /**
     * Register services.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::PACKAGE_DIR . '/config/multilingual.php', 'multilingual');
        $this->app['router']->mixin(new Router);
    }

    /**
     * Publishes migrations from stub files.
     *
     * @param string $className
     * @param string $fileName
     * @param int $timestampSuffix
     */
    protected function publishesMigration(string $className, string $fileName, int $timestampSuffix)
    {
        if (!class_exists($className)) {
            $timestamp = (new DateTime())->format('Y_m_d_His') . $timestampSuffix;
            $stub = self::PACKAGE_DIR . "/database/migrations/{$fileName}.php.stub";
            $file = database_path('migrations/multilingual/' . $timestamp . "_{$fileName}.php");

            $this->publishes([$stub => $file], 'migrations');
        }
    }

    /**
     * Register Blade aliases.
     */
    private function bladeDirectives()
    {
        Blade::directive('forEachLocale', function ($expression) {
            $expression = empty($expression) ? '$locale' : $expression;

            return "<?php foreach(locales() as {$expression}){?>";
        });

        Blade::directive('endForEachLocale', function () {
            return "<?php } ?>";
        });
    }

    /**
     * Register Translation observers.
     */
    private function translationObserver()
    {
        Translation::observe(TranslationObserver::class);
    }
}
