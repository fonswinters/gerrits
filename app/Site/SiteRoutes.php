<?php

namespace App\Site;

use Illuminate\Support\Facades\Route;

final class SiteRoutes
{
    /**
     * Overrides kms package site routes
     */
    public static function kms(){
        Route::model('site', Site::class); //Explicit route model binding
        Route::resource('sites', SiteController::class);
    }
}