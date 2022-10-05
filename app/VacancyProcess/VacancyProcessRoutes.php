<?php

namespace App\VacancyProcess;

use App\Servicepoints\Models\Servicepoint;
use App\VacancyProcess\Kms\VacancyProcessController;
use App\VacancyProcess\Models\VacancyProcess;
use Illuminate\Support\Facades\Route;
use App\Servicepoints\Kms\ServicepointController as KmsServicepointController;

final class VacancyProcessRoutes
{

    /**
     * Routes that are used for and within the KMS
     * Will be prefixed with 'kms' by the Route::group in the RouteResolver
     *
     * Note: Within the group we use the Explicit Route Model Binding to point to a Servicepoint
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('vacancy_process', VacancyProcess::class); //Explicit route model binding
        Route::resource('vacancy_process', VacancyProcessController::class);
        Route::get('api/vacancy_process', VacancyProcessController::class.'@getStructureAsJson');
        Route::post('api/vacancy_process', VacancyProcessController::class.'@setStructureAsJson');
    }

}