<?php

namespace OzanAkman\Multilingual\Models\Traits;

use OzanAkman\Multilingual\Models\Translation;

/**
 * Trait HasMultilingualContent
 * @package OzanAkman\Multilingual\Traits
 * @method HasMultilingualContent hasOne($model, $foreign_key)
 * @method HasMultilingualContent where($column, $value)
 */
trait HasMultilingualContent
{
    /**
     * Get translated version of the model by the given values.
     * @param null|string $locale
     * @return mixed
     */
    public function translation($locale = null)
    {
        return $this->hasOne(Translation::class, 'content_id')
            ->where('locale', $locale ?? config('multilingual.default_locale'));
    }
}