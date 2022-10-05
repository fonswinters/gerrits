<?php


namespace App\Development;

use Illuminate\Support\Facades\Route;

/**
 * Class TestApiRoutes
 *
 * These routes are used by e2e test software like cypress. To setup its environment.
 *
 * @package App\Development
 */
final class TestApiRoutes
{
    public static function web(){
        // Only make the route accessible when not in production
        if(\App::environment() != 'production') {
            Route::prefix('testapi/v1/languages')
                ->group(function () {
                    Route::get('current_site', TestApiController::class.'@currentSiteLanguages');
                    Route::get('having_sites', TestApiController::class.'@languagesHavingSites');
                });

            //Components
            Route::prefix('testapi/v1/components')
                ->group(function () {
                    Route::get('index', TestApiController::class.'@getComponents');
                });

            //KMS Users
            Route::prefix('testapi/v1/kms_users')
            ->group(function () {
                Route::get('show/{user?}', TestApiController::class.'@showKmsUser');
                Route::get('create', TestApiController::class.'@createKmsUser');
                Route::delete('{kms_user}', TestApiController::class.'@deleteKmsUser');
            });

            //Site Users
            Route::prefix('testapi/v1/site_users')
            ->group(function () {
                Route::get('show/{user?}', TestApiController::class.'@showSiteUser');
                Route::get('create', TestApiController::class.'@createSiteUser');
                Route::delete('{site_user}', TestApiController::class.'@deleteSiteUser');
            });

            //Documents
            Route::prefix('testapi/v1/documents')
                ->group(function () {
                    Route::get('index', TestApiController::class.'@indexDocuments');
                    Route::delete('{document}}', TestApiController::class.'@deleteDocument');
                });

            //Sites
            Route::prefix('testapi/v1/sites')
                ->group(function () {
                    Route::get('index', TestApiController::class.'@indexSites');
                });

            //Region info
            Route::prefix('testapi/v1/region_info')
                ->group(function () {
                    Route::get('random', TestApiController::class.'@randomRegionInfo');
                });

            //Mail interception routes
            Route::prefix('testapi/v1/mail_intercept')
                ->group(function () {
                    Route::get('enable', TestApiController::class.'@enableMailIntercepts');
                    Route::get('disable', TestApiController::class.'@disableMailIntercepts');
                    Route::get('get', TestApiController::class.'@getInterceptedMails');
                });


        }
    }
}