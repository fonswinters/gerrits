<?php

namespace App\Services;

use App\Services\Models\Service;
use Illuminate\Support\Facades\Route;
use App\Services\Kms\ServiceController as KmsServiceController;

final class ServiceRoutes
{

    /**
     * Route that are resolved from the Alias route to Rest Route by the AliasMiddleware.
     * Through the controller the models will be bind by Implicit Route Model Binding,
     * so no need to add Route::model in here.
     */
    public static function web(){
        Route::resource('services', ServiceController::class, [
            'only' => [
                'index',
                'show'
            ]
        ]);
    }

    /**
     * Routes that are used for and within the KMS
     * Will be prefixed with 'kms' by the Route::group in the RouteResolver
     *
     * Note: Within the group we use the Explicit Route Model Binding to point to a Service
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('service', Service::class); //Explicit route model binding
        Route::resource('services', KmsServiceController::class);
        Route::get('api/services', KmsServiceController::class.'@getStructureAsJson');
        Route::post('api/services', KmsServiceController::class.'@setStructureAsJson');
    }

}