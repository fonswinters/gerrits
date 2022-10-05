<?php

namespace App\Providers;

use App\Composers\NavItemComposer;
use App\Composers\LogoComposer;
use App\Composers\SidebarMenuComposer;
use App\Composers\VacancyListComposer;
use App\Composers\ButtonComposer;
use App\Routes\BreadcrumbComposer;
use App\TeamMembers\TeamMemberComposer;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('KMS::partials.sidebar', SidebarMenuComposer::class);

        view()->composer([
            'components.navigation',
            'organisms.overlayMenu',
            'organisms.footer'
            ], NavItemComposer::class);

        view()->composer('master', LogoComposer::class);
        view()->composer('organisms.vacancyList', VacancyListComposer::class);
        view()->composer('components.button', ButtonComposer::class);
        view()->composer('organisms.team', TeamMemberComposer::class.'@getAll');
        view()->composer('components.richSnippets', BreadcrumbComposer::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}