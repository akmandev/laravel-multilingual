<?php

namespace OzanAkman\Multilingual\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Translation.
 * @property $id
 * @property $content_id
 * @property $locale
 * @property $model
 * @property $slug
 * @property $content
 * @property $created_at
 * @property $updated_at
 */
class Translation extends Model
{
    protected $casts = [
        'content' => 'array',
    ];
}
