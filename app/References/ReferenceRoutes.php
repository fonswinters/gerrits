<?php

namespace App\References;

use App\References\Models\Reference;
use Illuminate\Support\Facades\Route;
use App\References\Kms\ReferenceController as KmsReferenceController;

final class ReferenceRoutes
{


    public static function web(){
        Route::get('/references', ReferenceController::class.'@show');

    }

    /**
     * Routes that are used for and within the KMS
     * Will be prefixed with 'kms' by the Route::group in the RouteResolver
     *
     * Note: Within the group we use the Explicit Route Model Binding to point to a Reference
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('reference', Reference::class); //Explicit route model binding
        Route::resource('references', KmsReferenceController::class);
    }

}