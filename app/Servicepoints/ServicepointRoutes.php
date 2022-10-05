<?php

namespace App\Servicepoints;

use App\Servicepoints\Models\Servicepoint;
use Illuminate\Support\Facades\Route;
use App\Servicepoints\Kms\ServicepointController as KmsServicepointController;

final class ServicepointRoutes
{

    /**
     * Routes that are used for and within the KMS
     * Will be prefixed with 'kms' by the Route::group in the RouteResolver
     *
     * Note: Within the group we use the Explicit Route Model Binding to point to a Servicepoint
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('servicepoint', Servicepoint::class); //Explicit route model binding
        Route::resource('servicepoints', KmsServicepointController::class);
    }

}