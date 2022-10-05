<?php
namespace Database\Factories;

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

use Komma\KMS\Globalization\RegionInfo;
use Komma\KMS\Users\Genders;
use Komma\KMS\Users\Models\KmsUser;
use Komma\KMS\Users\Models\KmsUserRole;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

$factory->define(KmsUser::class, function (Faker\Generator $faker) {
    /** @var RegionInfo $culture */
    $culture = $faker->culture();

    return [
        'role' => $faker->randomElement(KmsUserRole::getAsArray()),
        'password' => bcrypt(Str::random(10)),
        'email' => $faker->safeEmail,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'telephone' => $faker->phoneNumber,
        'culture' => $culture->getName(),
        'remember_token' => Str::random(10),
        'gender' => Arr::random(Genders::getAsArray()),
    ];
});
