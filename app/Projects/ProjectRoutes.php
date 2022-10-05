<?php

namespace App\Projects;

use App\Projects\Models\Project;
use Illuminate\Support\Facades\Route;
use App\Projects\Kms\ProjectController as KmsProjectController;

final class ProjectRoutes
{

    /**
     * Route that are resolved from the Alias route to Rest Route by the AliasMiddleware.
     * Through the controller the models will be bind by Implicit Route Model Binding,
     * so no need to add Route::model in here.
     */
    public static function web(){
        Route::resource('projects', ProjectController::class, [
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
     * Note: Within the group we use the Explicit Route Model Binding to point to a Project
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('project', Project::class); //Explicit route model binding
        Route::resource('projects', KmsProjectController::class);
        Route::get('api/projects', KmsProjectController::class.'@getStructureAsJson');
        Route::post('api/projects', KmsProjectController::class.'@setStructureAsJson');
    }

}