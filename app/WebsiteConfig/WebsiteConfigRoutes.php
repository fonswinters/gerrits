<?php


namespace App\WebsiteConfig;


use App\WebsiteConfig\Model\WebsiteConfig;
use Illuminate\Support\Facades\Route;

final class WebsiteConfigRoutes
{

    /**
     * Routes that are used for and within the KMS
     * Will be prefixed with 'kms' by the Route::group in the RouteResolver
     *
     * Note: Within the group we use the Explicit Route Model Binding to point to a Page
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('websiteconfig', WebsiteConfig::class); //Explicit route model binding

        Route::resource('/websiteconfig', WebsiteConfigController::class);
    }

}