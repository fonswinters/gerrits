<?php
namespace Database\Seeders;

use Komma\KMS\Globalization\Languages\Models\Language;
use App\Vacancies\Models\VacancyTranslation;
use App\Pages\Models\Page;
use App\Pages\Models\PageTranslation;
use App\Routes\RouteService;
use App\Site\Site;
use Komma\KMS\Sites\SiteServiceInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class DefaultSiteDemoPageSeeder extends Seeder
{
    /**
     * Run the seed
     */
    public function run()
    {
        //Get the needed services
        $routeService = new RouteService();
        $siteService = App::make(SiteServiceInterface::class); //Returns a singleton that is used throughout the whole app

        //Get the default site
        /** @var Site $site */
        $site = Site::where('slug', '=', 'default')->first();

        //Get the languages
        $languages = $site->languages()->get();  //Language::whereIn('iso_2', ['nl', 'en'])->get(['id', 'iso_2']);

        //Create the root page
        $siteRootPage = new Page(['active' => '0', 'site_id' => $site->id]);
        $siteRootPage->makeRoot();

        $homePage = $this->createHomePage($siteRootPage, $site, $languages, $routeService);
        $aboutUsPage = $this->createAboutUsPage($siteRootPage, $site, $languages, $routeService);
        $servicesPage = $this->createServicesPage($siteRootPage, $site, $languages, $routeService);
        $workFlowPage = $this->createWorkFlowPage($siteRootPage, $site, $languages, $routeService);
        $projectsPage = $this->createProjectsPage($siteRootPage, $site, $languages, $routeService);
        $newsPage = $this->createNewsPage($siteRootPage, $site, $languages, $routeService);
        $eventsPage = $this->createEventsPage($siteRootPage, $site, $languages, $routeService);
        $vacancyPage = $this->createVacanciesPage($siteRootPage, $site, $languages, $routeService);
        $testimonialsPage = $this->createReferencesPage($siteRootPage, $site, $languages, $routeService);
        $contactPage = $this->createContactPage($siteRootPage, $site, $languages, $routeService);
        $termsPage = $this->createTermsPage($siteRootPage, $site, $languages, $routeService);
        $privacyStatementPage = $this->createPrivacyStatementPage($siteRootPage, $site, $languages, $routeService);
        $disclaimerPage = $this->createDisclaimerPage($siteRootPage, $site, $languages, $routeService);
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createHomePage(Page $rootPage, Site $site, Collection $languages, RouteService $routeService): Page
    {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'home',
            'inNav' => 1,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if ($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'home',
                'name' => 'Home',
                'hero_title' => 'Plaats hier je <br> mooie streamer',
                'hero_description' => 'Dit is mooi!',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if ($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'home',
                'name' => 'Home',
                'hero_title' => 'Place your <br> beautiful streamer here',
                'hero_description' => 'This is nice!',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the German translation
        $language = $languages->where('id', '50')->first();
        if ($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'home',
                'name' => 'Home',
                'hero_title' => 'Platzieren Sie Ihren <br>wunderschönen Streamer hier',
                'hero_description' => 'Das ist nett!',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createAboutUsPage(
        Page $rootPage,
        Site $site,
        Collection $languages,
        RouteService $routeService
    ): Page {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'about',
            'inNav' => 1,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $pageTranslation = new PageTranslation([
            'slug' => 'over-ons',
            'name' => 'Over ons',
            'hero_title' => 'Good food = Good mood',
            'hero_active' => 1,
            'hero_description' => '<p>Introductie van het bedrijf. Corned beef ham tenderloin pastrami buffalo pig ham hock turducken biltong meatloaf leberkas t-bone pork belly pork burgdoggen. Cupim brisket pig drumstick prosciutto strip steak bacon turkey pork loin. Salami short ribs kielbasa short loin venison biltong. Kevin corned beef landjaeger, chicken swine pig short ribs picanha shank rump tongue flank pancetta doner jerky.</p>',
        ]);
        $pageTranslation->language()->associate($language);
        $pageTranslation->translatable()->associate($page);
        $pageTranslation->save();

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createServicesPage(
        Page $rootPage,
        Site $site,
        Collection $languages,
        RouteService $routeService
    ): Page {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'services',
            'has_wildcard' => true,
            'inNav' => 1,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'diensten',
                'name' => 'Diensten',
                'hero_title' => 'Header in de hero',
                'hero_description' => '<p>Hero omschrijving tekst. Phasellus ullamcorper ipsum rutrum nunc. Aenean commodo ligula eget dolor. Cras varius. Aenean tellus metus, bibendum sed, posuere ac, mattis non, nunc. Suspendisse potenti. Morbi nec metus. Praesent turpis. Pellentesque ut neque. Phasellus gravida semper nisi. Nulla sit amet est. Praesent venenatis metus at tortor pulvinar varius. Ut leo. In dui magna, posuere eget, vestibulum et, tempor auctor, justo. Curabitur suscipit suscipit tellus.</p>',
                'hero_button_id' => '2',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'services',
                'name' => 'Services',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createWorkFlowPage(
        Page $rootPage,
        Site $site,
        Collection $languages,
        RouteService $routeService
    ): Page {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'workflow',
            'servicepoint_heading' => 'Wij werken zorgvuldig en efficiënt.',
            'inNav' => 1,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if ($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'werkwijze',
                'name' => 'Werkwijze',
                'hero_title' => 'Efficiëntie gegarandeerd',
                'hero_description' => '<p>Introductie m.b.t. de werkwijze van het bedrijf. Spicy jalapeno bacon ipsum dolor amet bacon pancetta turkey buffalo pastrami pork belly sausage. Meatloaf ham hock picanha fatback buffalo shank frankfurter pancetta kielbasa. Short loin ham shankle pancetta jerky frankfurter turducken brisket kevin.</p>',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if ($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'workflow',
                'name' => 'Workflow',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createProjectsPage(
        Page $rootPage,
        Site $site,
        Collection $languages,
        RouteService $routeService
    ): Page {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'projects',
            'has_wildcard' => true,
            'inNav' => 1,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if ($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'projecten',
                'name' => 'Projecten',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if ($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'projects',
                'name' => 'Projects',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createNewsPage(Page $rootPage, Site $site, Collection $languages, RouteService $routeService): Page
    {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'posts',
            'has_wildcard' => true,
            'inNav' => 1,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'nieuws',
                'name' => 'Nieuws',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'news',
                'name' => 'News',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }


    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createEventsPage(Page $rootPage, Site $site, Collection $languages, RouteService $routeService): Page
    {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'events',
            'has_wildcard' => true,
            'inNav' => 1,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'events',
                'name' => 'Events',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'events',
                'name' => 'Events',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }


    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createReferencesPage(
        Page $rootPage,
        Site $site,
        Collection $languages,
        RouteService $routeService
    ): Page {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'references',
            'inNav' => 1,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'referenties',
                'name' => 'Referenties',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'testimonials',
                'name' => 'Testimonials',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createContactPage(
        Page $rootPage,
        Site $site,
        Collection $languages,
        RouteService $routeService
    ): Page {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'contact',
            'inNav' => 1,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'contact',
                'name' => 'Contact',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'contact',
                'name' => 'Contact',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createTermsPage(
        Page $rootPage,
        Site $site,
        Collection $languages,
        RouteService $routeService
    ): Page {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'legal',
            'inNav' => 0,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'algemene-voorwaarden',
                'name' => 'Algemene voorwaarden',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'terms-and-conditions',
                'name' => 'Terms and conditions',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createPrivacyStatementPage(
        Page $rootPage,
        Site $site,
        Collection $languages,
        RouteService $routeService
    ): Page {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'privacy',
            'inNav' => 0,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'privacyverklaring',
                'name' => 'Privacyverklaring',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'privacy-statement',
                'name' => 'Privacy statement',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the german translation
        $language = $languages->where('id', '50')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'datenschutzerklarung',
                'name' => 'Datenschutzerklärung',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createDisclaimerPage(
        Page $rootPage,
        Site $site,
        Collection $languages,
        RouteService $routeService
    ): Page {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'disclaimer',
            'inNav' => 0,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'disclaimer',
                'name' => 'Disclaimer',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the English translation
        $language = $languages->where('id', '40')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'disclaimer',
                'name' => 'Disclaimer',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        //...the German translation
        $language = $languages->where('id', '50')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'haftungsausschluss',
                'name' => 'Haftungsausschluss',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }

    /**
     * @param Page $rootPage
     * @param Site $site
     * @param Collection $languages
     * @param RouteService $routeService
     * @return Page
     */
    private function createVacanciesPage(
        Page $rootPage,
        Site $site,
        Collection $languages,
        RouteService $routeService
    ): Page {
        //Create the page itself...
        $page = new Page([
            'active' => 1,
            'code_name' => 'vacancies',
            'has_wildcard' => true,
            'inNav' => 1,
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        if($language) {
            $pageTranslation = new PageTranslation([
                'slug' => 'Vacatures',
                'name' => 'Vacatures',
                'description' => 'Onze vacatures',
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($page);
            $pageTranslation->save();
        }

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }
}









