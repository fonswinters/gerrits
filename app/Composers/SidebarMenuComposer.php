<?php namespace App\Composers;

use App\Buttons\Models\Button;
use App\WebsiteConfig\Model\WebsiteConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Komma\KMS\Menu\KmsMenuItem;
use Komma\KMS\Sites\Models\Site;
use Komma\KMS\Sites\SiteServiceInterface;
use Komma\KMS\Users\Models\KmsUser;

class SidebarMenuComposer
{
    private SiteServiceInterface $siteService;

    public function __construct()
    {
        $this->siteService = app(SiteServiceInterface::class);
    }

    public function compose($view)
    {
        if(!Auth::user()) return;
        //Get the view data that contains the slug
        $viewData = $view->getData();
        $currentSectionSlug = isset($viewData['slug']) ? $viewData['slug'] : $this->getCurrentSectionSlug();

        //Get the current site.
        /** @var SiteServiceInterface $siteService */
        $siteService = app(SiteServiceInterface::class);
        $sites = $siteService->getSites();
        $currentSite = $siteService->getCurrentSite();

        $kmsMenu = collect();

        $kmsMenu->push((new KmsMenuItem())
            ->setName(__('KMS::sidebarMenu.welcome'))
            ->setUrl(route('dashboard.index'))
            ->setModelSlug('dashboard'));

        // If we have multiple site load get the siteMenus, else add the items directly
        if(config('app.multipleSites')) $kmsMenu = $kmsMenu->merge($this->getSiteMenus());
        else $kmsMenu = $kmsMenu->merge($this->getSiteSubItems($this->siteService->getSite()));

        //Create regular menu items.
//        $kmsMenu->push((new KmsMenuItem())
//            ->setModelSlug('posts')
//            ->setUrl(route('posts.index'))
//            ->setName(__('KMS::sidebarMenu.posts')));

//        $kmsMenu->push((new KmsMenuItem())
//            ->setModelSlug('events')
//            ->setUrl(route('events.index'))
//            ->setName(__('KMS::sidebarMenu.events')));

        $kmsMenu->push((new KmsMenuItem())
            ->setModelSlug('services')
            ->setUrl(route('services.index'))
            ->setName(__('KMS::sidebarMenu.services')));

        $kmsMenu->push((new KmsMenuItem())
            ->setModelSlug('projects')
            ->setUrl(route('projects.index'))
            ->setName(__('KMS::sidebarMenu.projects')));

//        $kmsMenu->push((new KmsMenuItem())
//            ->setModelSlug('references')
//            ->setUrl(route('references.index'))
//            ->setName(__('KMS::sidebarMenu.references')));

        $kmsMenu->push((new KmsMenuItem())
            ->setModelSlug('team_members')
            ->setUrl(route('team_members.index'))
            ->setName(__('KMS::sidebarMenu.team_members')));

//        $kmsMenu->push((new KmsMenuItem())
//            ->setName(__('KMS::sidebarMenu.vacancies'))
//            ->setSubItems(collect([
//                (new KmsMenuItem())
//                    ->setModelSlug('vacancy_process')
//                    ->setUrl(route('vacancy_process.index'))
//                    ->setName(__('KMS::sidebarMenu.vacancy_process')),
//
//                (new KmsMenuItem())
//                    ->setModelSlug('vacancies')
//                    ->setUrl(route('vacancies.index'))
//                    ->setName(__('KMS::sidebarMenu.vacancies'))
//            ])));

        $kmsMenu->push((new KmsMenuItem())->setIsSeparator(true));

        if(Auth::user()->can('index', WebsiteConfig::class)) {
            $kmsMenu->push((new KmsMenuItem())
                ->setModelSlug('websiteconfig')
                ->setUrl(route('websiteconfig.index'))
                ->setName(__('KMS::sidebarMenu.websiteconfig')));
        }

        if (Auth::user()->can('index', Button::class)) {
            $kmsMenu->push((new KmsMenuItem())
                ->setModelSlug('buttons')
                ->setUrl(route('buttons.index'))
                ->setName(__('KMS::sidebarMenu.buttons')));

            $kmsMenu->push((new KmsMenuItem())
                ->setModelSlug('servicepoints')
                ->setUrl(route('servicepoints.index'))
                ->setName(__('KMS::sidebarMenu.servicepoints')));
        }

        $kmsMenu->push((new KmsMenuItem())
            ->setModelSlug('kms_users')
            ->setUrl(route('kms_users.index'))
            ->setName(__('KMS::sidebarMenu.kms_users')));

        if (Auth::user() && Auth::user('kms')->can('editSites', KmsUser::class)) {
            $kmsMenu->push((new KmsMenuItem())->setName(__('KMS::sidebarMenu.sites'))
                ->setUrl(route('sites.index'))
                ->setModelSlug('sites'));
        }

        $view->with('kmsMenu', $kmsMenu);
    }

    /**
     * Get the site menus
     *
     * @return Collection
     */
    private function getSiteMenus(): Collection
    {
        $sites = $this->siteService->getSites();

        // Else we create a site menu for each site
        $siteMenus = collect();

        /** @var Site $site */
        foreach ($sites as $site) {
            if(!$site->exists) continue;

            $siteMenus->push((new KmsMenuItem())
                ->setName($site->name)
                ->setSubItems($this->getSiteSubItems($site)));
        }

        return $siteMenus;
    }

    /**
     * This will return the site specific items
     *
     * @param Site $site
     * @return Collection
     */
    protected function getSiteSubItems(Site $site)
    {
        $siteMenuItems = collect();

        $siteMenuItems->push((new KmsMenuItem())
            ->setName(__('KMS::sidebarMenu.pages'))
            ->setUrl(route('pages.index', ['siteSlug' => $site->slug]))
            ->setModelSlug('pages')
            ->setSiteSlug($site->slug));

        return $siteMenuItems;
    }

    /**
     * @return |null
     */
    protected function getCurrentSectionSlug()
    {
        $route = explode('/', Route::current()->uri());
        if (Route::current()->parameter('site')) {
            return $route[2];
        }

        return isset($route[1]) ? $route[1] : null;
    }

    /**
     * @return |null
     */
    protected function getCurrentSectionSubSlug()
    {
        $route = explode('/', Route::current()->uri());
        if (Route::current()->parameter('site')) {
            if (isset($route[3])) {
                return $route[3];
            }
        }

        return isset($route[2]) ? $route[2] : null;
    }
}