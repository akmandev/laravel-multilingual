<?php

use Faker\Generator as Faker;
use OzanAkman\Multilingual\Models\Locale;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Locale::class, function (Faker $faker) {
    return [
        'code' => $faker->languageCode,
    ];
});
