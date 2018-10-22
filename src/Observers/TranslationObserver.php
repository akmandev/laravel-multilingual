<?php

namespace OzanAkman\Multilingual\Observers;

use Cocur\Slugify\Slugify;
use OzanAkman\Multilingual\Models\Translation;

class TranslationObserver
{
    /**
     * Handle the Translation "saving" event.
     * @param \OzanAkman\Multilingual\Models\Translation $translation
     * @throws \Exception
     */
    public function saving(Translation $translation)
    {
        $reflection = new \ReflectionClass($translation->model);

        if (method_exists($reflection, 'slugSource')) {
            $locale = $this->getLocale($translation);
            $slugify = new Slugify(['rulesets' => ['default', $locale]]);

            $translation->slug = $slugify->slugify($translation->content->slug_source);
        }
    }

    /**
     * @param $translation
     * @return string
     * @throws \Exception
     */
    private function getLocale($translation)
    {
        return strtolower(locales()->where('code', $translation->locale)->value('name'));
    }
}