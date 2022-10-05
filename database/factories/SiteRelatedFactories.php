<?php
namespace Database\Factories;

use Komma\KMS\Sites\Models\Site;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(Site::class, function (Faker\Generator $faker) {
    $name = $faker->word;

    return [
        'slug' => Str::slug($name),
        'name' => $name,
        'default_language_id' => 104,
    ];
});