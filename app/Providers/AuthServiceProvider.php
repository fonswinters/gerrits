<?php

namespace App\Providers;

use App\Buttons\Models\Button;
use App\Events\Models\Event;
use App\Pages\Models\Page;
use App\Pages\PagePolicy;
use App\Posts\Models\Post;
use App\Projects\Models\Project;
use App\References\Models\Reference;
use App\Servicepoints\Models\Servicepoint;
use App\Services\Models\Service;
use App\Vacancies\Models\Vacancy;
use App\TeamMembers\Models\TeamMember;
use App\VacancyProcess\Models\VacancyProcess;
use App\WebsiteConfig\Model\WebsiteConfig;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Komma\KMS\ActionLog\Models\ActionLog;
use Komma\KMS\Base\Policy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ActionLog::class => Policy::class,
        Button::class => Policy::class,
        Page::class => PagePolicy::class,
        Post::class => Policy::class,
        Event::class => Policy::class,
        Project::class => Policy::class,
        Reference::class => Policy::class,
        Service::class => Policy::class,
        Servicepoint::class => Policy::class,
        Vacancy::class => Policy::class,
        TeamMember::class => Policy::class,
        WebsiteConfig::class => Policy::class,
        VacancyProcess::class => Policy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        parent::registerPolicies();
    }
}
