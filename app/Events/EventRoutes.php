<?php

namespace App\Events;

use App\Events\Models\Event;
use Illuminate\Support\Facades\Route;
use App\Events\Kms\EventController as KmsEventController;

final class EventRoutes
{

    /**
     * Route that are resolved from the Alias route to Rest Route by the AliasMiddleware.
     * Through the controller the models will be bind by Implicit Route Model Binding,
     * so no need to add Route::model in here.
     */
    public static function web(){

        Route::get('event/{eventSlug}/signup', EventController::class.'@success')->name('event.success');

        Route::resource('events', EventController::class, [
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
     * Note: Within the group we use the Explicit Route Model Binding to point to a Event
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('event', Event::class); //Explicit route model binding
        Route::resource('events', KmsEventController::class);
    }
}