<?php

namespace App\Providers;

use ComponentFaker;
use CultureFaker;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

/**
 * Class FakerServiceProvider
 *
 * Adds extra data sources to the faker
 *
 * @package App\Providers
 */
class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /** @var Generator $faker */
        app()->extend(Generator::class, function(Generator $faker) {
            $faker->addProvider(CultureFaker::class);
            $faker->addProvider(ComponentFaker::class);
            return $faker;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
