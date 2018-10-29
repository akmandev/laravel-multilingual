<?php

namespace OzanAkman\Multilingual\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Locale.
 * @property $code
 * @property $name
 * @property $native_name
 * @property $default
 * @property $enabled
 */
class Locale extends Model
{
    /**
     * Disable auto incrementing.
     * @var bool
     */
    public $incrementing = false;

    /**
     * Disable timestamps.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * Cast attributes.
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
        'default' => 'boolean',
    ];
}
