<?php

namespace App\Providers;

use App\Buttons\ButtonRoutes;
use App\Development\PreviewRoutes;
use App\Events\EventRoutes;
use App\Forms\FormRoutes;
use App\Pages\PageRoutes;
use App\Posts\PostRoutes;
use App\Projects\ProjectRoutes;
use App\References\ReferenceRoutes;
use App\Services\ServiceRoutes;
use App\Servicepoints\ServicepointRoutes;
use App\Sitemap\SitemapRoutes;
use App\TeamMembers\TeamMemberRoutes;
use App\Vacancies\VacancyRoutes;
use App\WebsiteConfig\WebsiteConfigRoutes;
use App\VacancyProcess\VacancyProcessRoutes;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(function () {
                Route::group(['middleware' => 'auth:site'], function() {

                });

                FormRoutes::web();
                PageRoutes::web();
                VacancyRoutes::web();
                PostRoutes::web();
                EventRoutes::web();
                ServiceRoutes::web();
                ProjectRoutes::web();
                ReferenceRoutes::web();
                PreviewRoutes::web();
                SitemapRoutes::web();
        });

        Route::middleware(['web', 'kms'])
            ->prefix('kms')
            ->group(function () {
                Route::group(['middleware' => 'auth:kms'], function() {
                    WebsiteConfigRoutes::kms();
                    PageRoutes::kms();
                    VacancyRoutes::kms();
                    PostRoutes::kms();
                    EventRoutes::kms();
                    ServiceRoutes::kms();
                    ProjectRoutes::kms();
                    ReferenceRoutes::kms();
                    TeamMemberRoutes::kms();
                    ButtonRoutes::kms();
                    ServicepointRoutes::kms();
                    VacancyProcessRoutes::kms();
                });
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => 'App\Komma',
            'prefix' => 'api',
        ], function ($router) {
//            require_once base_path('routes/api.php');
        });
    }
}