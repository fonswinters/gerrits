<?php


namespace App\Forms;

use App\Events\EventController;
use App\Vacancies\VacancyController;
use Illuminate\Support\Facades\Route;

final class FormRoutes
{

    /**
     * Route that are resolved from the Alias route to Rest Route by the AliasMiddleware.
     * Through the controller the models will be bind by Implicit Route Model Binding,
     * so no need to add Route::model in here.
     */
    public static function web(){

        Route::post('contact/process', ContactController::class.'@process')->name('contact.process');
        Route::get('contact/success', ContactController::class.'@success')->name('contact.success');

        Route::post('vacancy/process', VacancyController::class.'@process')->name('vacancy.process');
        Route::get('vacancy/success/{vacancy}', VacancyController::class.'@success')->name('vacancy.success');

        Route::post('event/process', EventController::class.'@process')->name('event.process');
    }

}