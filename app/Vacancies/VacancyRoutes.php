<?php

namespace App\Vacancies;

use App\Vacancies\Models\Vacancy;
use Illuminate\Support\Facades\Route;
use App\Vacancies\Kms\VacancyController as KmsVacancyController;

final class VacancyRoutes
{

    /**
     * Route that are resolved from the Alias route to Rest Route by the AliasMiddleware.
     * Through the controller the models will be bind by Implicit Route Model Binding,
     * so no need to add Route::model in here.
     */
    public static function web(){

        Route::get('vacancy/{vacancy}/apply', VacancyController::class.'@success')->name('vacancy.form.success');

        Route::resource('vacancies', VacancyController::class, [
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
     * Note: Within the group we use the Explicit Route Model Binding to point to a Vacancy
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('vacancy', Vacancy::class); //Explicit route model binding
        Route::resource('vacancies', KmsVacancyController::class);
        Route::get('api/vacancies', KmsVacancyController::class.'@getStructureAsJson');
        Route::post('api/vacancies', KmsVacancyController::class.'@setStructureAsJson');
    }

}