<?php


namespace App\Sitemap;

use Illuminate\Support\Facades\Route;


final class SitemapRoutes
{

    /**
     * Route that are resolved from the Alias route to Rest Route by the AliasMiddleware.
     * Through the controller the models will be bind by Implicit Route Model Binding,
     * so no need to add Route::model in here.
     */
    public static function web(){
        Route::get('sitemap', SitemapController::class.'@show');
        Route::get('{language}/sitemap', SitemapController::class.'@show');
        Route::get('sitemap.xml', SitemapController::class.'@xml');
    }
}



