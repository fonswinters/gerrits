<?php

namespace App\TeamMembers;

use App\TeamMembers\Models\TeamMember;
use Illuminate\Support\Facades\Route;
use App\TeamMembers\Kms\TeamMemberController as KmsTeamMemberController;

final class TeamMemberRoutes
{
    /**
     * Routes that are used for and within the KMS
     * Will be prefixed with 'kms' by the Route::group in the RouteResolver
     *
     * Note: Within the group we use the Explicit Route Model Binding to point to a TeamMember
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('team_member', TeamMember::class); //Explicit route model binding
        Route::resource('team_members', KmsTeamMemberController::class);
        Route::get('api/team_members', KmsTeamMemberController::class.'@getStructureAsJson');
        Route::post('api/team_members', KmsTeamMemberController::class.'@setStructureAsJson');
    }

}