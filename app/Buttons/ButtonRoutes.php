<?php

namespace App\Buttons;

use App\Buttons\Models\Button;
use Illuminate\Support\Facades\Route;
use App\Buttons\Kms\ButtonController as KmsButtonController;

final class ButtonRoutes
{

    /**
     * Routes that are used for and within the KMS
     * Will be prefixed with 'kms' by the Route::group in the RouteResolver
     *
     * Note: Within the group we use the Explicit Route Model Binding to point to a Button
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('button', Button::class); //Explicit route model binding
        Route::resource('buttons', KmsButtonController::class);
    }

}