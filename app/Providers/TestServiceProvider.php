<?php

namespace App\Providers;

use App\Development\PreviewRoutes;
use App\Development\TestApiRoutes;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;


class TestServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(function () {
                Route::group(['middleware' => 'auth:site'], function() {

                });

                PreviewRoutes::web(); //Also needed on production to verify stuff. And so that translators can translate mail for example.
                if(app()->environment() !== 'production') TestApiRoutes::web();
            });

        Route::middleware(['web', 'kms'])
            ->prefix('kms')
            ->group(function () {
                //Authenticated routes
                Route::group(['middleware' => 'auth:kms'], function() {
                });
            });
    }
}