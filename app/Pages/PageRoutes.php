<?php


namespace App\Pages;


use App\Pages\Models\Page;
use Illuminate\Support\Facades\Route;

use App\Pages\Kms\PageController as KmsPageController;

final class PageRoutes
{

    /**
     * Route that are resolved from the Alias route to Rest Route by the AliasMiddleware.
     * Through the controller the models will be bind by Implicit Route Model Binding,
     * so no need to add Route::model in here.
     */
    public static function web(){
        Route::get('/pages/{page}', PageController::class.'@show');
        Route::view('offline', 'templates.offline');

        // temporary for development
        Route::get('/styleguide', PageController::class.'@styleguide');
    }

    /**
     * Routes that are used for and within the KMS
     * Will be prefixed with 'kms' by the Route::group in the RouteResolver
     *
     * Note: Within the group we use the Explicit Route Model Binding to point to a Page
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('page', Page::class); //Explicit route model binding

        Route::resource('{siteSlug}/pages', KmsPageController::class);
        Route::get('api/{siteSlug}/pages', KmsPageController::class.'@getStructureAsJson');
        Route::post('api/{siteSlug}/pages', KmsPageController::class.'@setStructureAsJson');
    }

}