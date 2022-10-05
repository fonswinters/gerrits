<?php
namespace Database\Seeders;

use Komma\KMS\Globalization\Languages\Models\Language;
use App\Pages\Models\Page;
use App\Pages\Models\PageTranslation;
use App\Routes\RouteService;
use App\Site\Site;
use Komma\KMS\Sites\SiteServiceInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class SecondSitePageSeeder extends Seeder
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
        $site = Site::where('slug', '=', 'subsidiary-company')->first();

        //Get the languages
        $languages = $site->languages()->get();

        //Create the root page
        $siteRootPage = new Page(['active' => '0', 'site_id' => $site->id]);
        $siteRootPage->makeRoot();

        $homePage               = $this->createHomePage($siteRootPage, $site, $languages, $routeService);
        $aboutUsPage            = $this->createAboutUsPage($siteRootPage, $site, $languages, $routeService);
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
            'active'        => 1,
            'code_name'     => 'home',
            'inNav'         => 1
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the English translation
        $language = $languages->where('iso_2', 'en')->first();
        $pageTranslation = new PageTranslation([
            'slug'                => 'home',
            'name'                => 'Home',
            'meta_title'          => 'Home',
            'hero_title'          => 'Place your <br> beautiful streamer here',
            'hero_description'    => 'This is nice!',
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
    private function createAboutUsPage(Page $rootPage, Site $site, Collection $languages, RouteService $routeService): Page
    {
        //Create the page itself...
        $page = new Page([
            'active'        => 1,
            'code_name'     => 'about',
            'inNav'         => 1
        ]);

        $page->site()->associate($site);
        $page->makeLastChildOf($rootPage);
        $page->save();

        //...the English translation
        $language = $languages->where('iso_2', 'en')->first();
        $pageTranslation = new PageTranslation([
            'slug'                => 'about-us',
            'name'                => 'About us',
            'meta_title'          => 'About us | Subcompany',
            'hero_title'          => 'About the sub Company',
            'hero_active'         => 1,
            'hero_description'    => '<p>Introduction for the Subsidiary company. Corned beef ham tenderloin pastrami buffalo pig ham hock turducken biltong meatloaf leberkas t-bone pork belly pork burgdoggen. Cupim brisket pig drumstick prosciutto strip steak bacon turkey pork loin. Salami short ribs kielbasa short loin venison biltong. Kevin corned beef landjaeger, chicken swine pig short ribs picanha shank rump tongue flank pancetta doner jerky.</p>',
        ]);
        $pageTranslation->language()->associate($language);
        $pageTranslation->translatable()->associate($page);
        $pageTranslation->save();

        $routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        return $page;
    }
}









