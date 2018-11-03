<?php

namespace OzanAkman\Multilingual\Models\Traits;

use OzanAkman\Multilingual\Models\Translation;

/**
 * Trait HasMultilingualContent.
 * @method HasMultilingualContent hasOne($model, $foreign_key)
 * @method HasMultilingualContent where($column, $value)
 */
trait HasMultilingualContent
{
    /**
     * Get translated version of the model by the given values.
     * @param string $locale
     * @param array $attributes
     * @return mixed
     */
    public function translate($locale, $attributes = [])
    {
        if (! $attributes) {
            return $this->hasOne(Translation::class, 'content_id')
                ->where('locale', $locale);
        }

        $translation = new Translation();
        $translation->content_id = $this->getKey();
        $translation->locale = $locale;
        $translation->model = self::class;
        $translation->slug = method_exists('slugSource', $this) ? $this->slugSource() : null;
        $translation->content = $attributes;
        $translation->save();

        return $translation;
    }

    /**
     * Remove a translation from the model.
     * @param $locale
     */
    public function removeTranslation($locale)
    {
        $translation = Translation::where('content_id', $this->getKey())
            ->where('locale', $locale)
            ->first();

        if ($translation) {
            $translation->delete();
        }
    }
}
