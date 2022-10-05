<?php

namespace Tests\Unit;

use Komma\KMS\Core\ModelServiceInterface;
use Komma\KMS\Core\TranslationService;
use Komma\KMS\Core\TranslationServiceInterface;
use App\Pages\Kms\PageModelService;
use App\Pages\Models\Page;
use App\Pages\Models\PageTranslation;
use App\Routes\Models\Route;
use App\Routes\RouteService;
use Komma\KMS\Globalization\Languages\Models\Language;
use Komma\KMS\Sites\Kms\SiteService;
use Komma\KMS\Sites\Models\Site;
use Komma\KMS\Sites\SiteServiceInterface;;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use DatabaseTransactions; //Automatically rolls back database actions after tests
    use WithFaker;

    /** @var RouteService */
    private $routeService;

    /** @var SiteService */
    private $siteService;

    /** @var TranslationServiceInterface */
    private $translationService;

    /**
     * @group RouteService
     * @test
     */
    public function instanceCreationTest()
    {
        $this->assertInstanceOf(RouteService::class, $this->routeService);
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->routeService = new RouteService();
        $this->translationService = new TranslationService();
        $this->siteService = app(SiteServiceInterface::class);
        $this->siteService->setCurrentSiteToDefault();
    }

    /**
     * When you save a page a friendly url should be created.
     *
     * @group RouteService
     * @test
     */
    public function basicRouteCreationTestWithSiteThatHasSingleLanguage()
    {
        $languagesCount = $this->siteService->getCurrentSite()->languages()->count();
        if($languagesCount > 1) {
            $language = $this->siteService->getCurrentSite()->languages()->first();
            $this->siteService->getCurrentSite()->languages()->detach();
            $this->siteService->getCurrentSite()->languages()->attach($language);
        }

        //This test assumes that the current site has one languages (two prevent the language iso 2 being prefixed to each route)
        $this->assertTrue($this->siteService->getCurrentSite()->languages()->count() == 1);

        //Create a page translation with a page
        /** @var PageTranslation $pageTranslation */
        $pageTranslation = factory(PageTranslation::class)->create();
        $language = $pageTranslation->language()->first();

        /** @var Page $page */
        $page = $pageTranslation->translatable()->first();

        //Validate that the page translation did not have a route already
        self::assertEquals(0, $pageTranslation->route()->count());
        self::assertEquals(0, $pageTranslation->redirectRoutes()->count());

        //Give the page to the routeService. I now must create a route and link it to the page
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        //Check that the translation has a route...
        self::assertEquals(1, $pageTranslation->route()->count());

        //...And it does not have a redirect route.
        self::assertEquals(0, $pageTranslation->redirectRoutes()->count());

        //Get the route so we can check its attributes
        $route = $pageTranslation->route()->first();

        $this->assertEquals('pages/'.$page->id, $route->route);
        $this->assertEquals('/'.Str::slug($pageTranslation->name), $route->alias);
        $this->assertEquals($pageTranslation->id, $route->routable_id);
        $this->assertEquals(get_class($pageTranslation), $route->routable_type);
        $this->assertEquals($this->siteService->getCurrentSite()->id, $route->site_id);
        $this->assertEquals($language->id, $route->language_id);
    }

    /**
     * When you save a page a friendly url should be created.
     *
     * @group RouteService
     * @test
     */
    public function basicRouteCreationTest()
    {
        $languagesCount = $this->siteService->getCurrentSite()->languages()->count();

        //Create a page translation with a page and make it a child of the first root page
        /** @var PageTranslation $pageTranslation */
        $pageTranslation = factory(PageTranslation::class)->create();
        $language = $pageTranslation->language()->first();

        /** @var Page $page */
        $page = $pageTranslation->translatable()->first();

        $rootPage = Page::where('lft', '=', '1')->first();
        $page->makeLastChildOf($rootPage);
        $page->save();

        //Validate that the page translation did not have a route already
        self::assertEquals(0, $pageTranslation->route()->count());
        self::assertEquals(0, $pageTranslation->redirectRoutes()->count());

        //Give the page to the routeService. It now must create a route and link it to the page
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        //Check that the translation has a route...
        self::assertEquals(1, $pageTranslation->route()->count());

        //...And it does not have a redirect route.
        self::assertEquals(0, $pageTranslation->redirectRoutes()->count());

        //Get the route so we can check its attributes
        $route = $pageTranslation->route()->first();

        $this->assertEquals('pages/'.$page->id, $route->route);
        if($languagesCount >= 2) {
            $this->assertEquals('/' . $language->iso_2 . '/' . Str::slug($pageTranslation->name), $route->alias);
        } else {
            $this->assertEquals('/' . Str::slug($pageTranslation->name), $route->alias);
        }
        $this->assertEquals($pageTranslation->id, $route->routable_id);
        $this->assertEquals(get_class($pageTranslation), $route->routable_type);
        $this->assertEquals($this->siteService->getCurrentSite()->id, $route->site_id);
        $this->assertEquals($language->id, $route->language_id);


        //Create another page translation with another page
        /** @var PageTranslation $pageTranslationTwo */
        $pageTranslationTwo = factory(PageTranslation::class)->create();
        $language = $pageTranslation->language()->first();

        /** @var Page $pageTwo */
        $pageTwo = $pageTranslationTwo->translatable()->first();

        //Make pageTwo a child of one.
        $pageTwo->makeLastChildOf($page);
        $pageTwo->save();

        //Give the page to the routeService. It now must create a route with the alias of page suffixed with the slug / name of pageTwo
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($pageTwo);
        $routePageTwo = $pageTranslationTwo->route()->first();

        $this->assertNotNull($routePageTwo);
        $routePageOne = $pageTranslation->route()->first();
        $this->assertNotNull($routePageOne);

        $this->assertEquals($routePageOne->alias.'/'.$pageTranslationTwo->slug, $routePageTwo->alias);
    }

    /**
     * When you edit a translations slug / name the route changes too to reflect the new slug / name.
     * This is done by creating a new route with the new changes in it. The old routes is converted in a redirect route
     * so that users using the old route will be redirected using the redirect route, to the new route.
     *
     * @group RouteService
     * @test
     */
    public function basicRedirectRouteCreationTest()
    {
        //This test assumes that the current site has at least two languages (for the route alias prefix)
        $languagesCount = $this->siteService->getCurrentSite()->languages()->count();

        //Create a page translation with a page
        /** @var PageTranslation $pageTranslation */
        $pageTranslation = factory(PageTranslation::class)->create();
        $language = $pageTranslation->language()->first();

        /** @var Page $page */
        $page = $pageTranslation->translatable()->first();

        //Validate that the page translation did not have a route already
        self::assertEquals(0, $pageTranslation->route()->count());
        self::assertEquals(0, $pageTranslation->redirectRoutes()->count());

        //Give the page to the routeService. I now must create a route and link it to the page
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        //Edit the page translation and save it. But keep track of the old slug and name for later
        $newName = $this->faker->name;
        $oldSlug = $pageTranslation->slug;
        $oldName = $pageTranslation->name;
        $pageTranslation->slug = Str::slug($newName);
        $pageTranslation->name = $newName;
        $pageTranslation->save();

        //Hand the page to route service again. It must detect that the page translation was changed and will convert the current translation route to a redirect route.
        //And it must create a new route for the translation.
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);

        //Check that the translation has a new route...
        self::assertEquals(1, $pageTranslation->route()->count());

        //...And it now does have a redirect route
        self::assertEquals(1, $pageTranslation->redirectRoutes()->count());

        //Check that the new route matches the details of the translation
        $newRoute = $pageTranslation->route()->first();
        $this->assertEquals('pages/'.$page->id, $newRoute->route);
        if($languagesCount >= 2) {
            $this->assertEquals('/'.$language->iso_2.'/'.Str::slug($pageTranslation->name), $newRoute->alias);
        } else {
            $this->assertEquals('/'.Str::slug($pageTranslation->name), $newRoute->alias);
        }
        $this->assertEquals($pageTranslation->id, $newRoute->routable_id);
        $this->assertEquals(get_class($pageTranslation), $newRoute->routable_type);
        $this->assertEquals($this->siteService->getCurrentSite()->id, $newRoute->site_id);
        $this->assertEquals($language->id, $newRoute->language_id);

        //Check the redirect route is the same as the old route's details
        $redirectRoute = $pageTranslation->redirectRoutes()->first();
        $this->assertEquals('pages/'.$page->id, $redirectRoute->route);
        if($languagesCount >= 2) {
            $this->assertEquals('/' . $language->iso_2 . '/' . Str::slug($oldName), $redirectRoute->alias);
        } else {
            $this->assertEquals('/' . Str::slug($oldName), $redirectRoute->alias);
        }
        $this->assertEquals($pageTranslation->id, $redirectRoute->routable_id);
        $this->assertEquals(get_class($pageTranslation), $redirectRoute->routable_type);
        $this->assertEquals($this->siteService->getCurrentSite()->id, $redirectRoute->site_id);
        $this->assertEquals($language->id, $redirectRoute->language_id);
    }

    /**
     * Some routes potentially have the same alias as another route. Because they MAY be the same when they belong to
     * a different site, we test that they are the same.
     *
     * Example: Consider two pageTranslations which have exactly the same name and slug of "test". And they each belong
     * to a different Page. And both of those Pages have a different Site. Both sites have two languages, Dutch and English.
     * In that case their route aliases must be the same. Because they are on a different site with a different domain.
     * In this example the aliases must be: nl/test and en/test
     *
     * @group RouteService
     * @test
     */
    public function testRoutesForTranslationsWithDifferentSiteAndPage()
    {
        //Check that we have at least two sites. Only then we can execute this test and does it make sense to do so.
        if(Site::count() < 2) {
            $sitesToCreate = 2 - Site::count();
            for($index = 0; $index < $sitesToCreate; $index++) {
                /** @var Site $site */
                $site = factory(Site::class)->create();
                $languages = Language::inRandomOrder()->take(2)->get();
                $site->languages()->saveMany($languages);
            }
        }

        //Check that the current site has 2 languages. Only then we can execute this that and does it makes sense to do so.
        if($this->siteService->getCurrentSite()->languages()->count() < 2) {
            $languagesToCreate = 2 - $this->siteService->getCurrentSite()->languages()->count();
            for($index = 0; $index < $languagesToCreate; $index++) {
                $language = Language::inRandomOrder()->first(['id']);
                $this->siteService->getCurrentSite()->languages()->attach($language);
            }
        }

        //This test assumes that the current site has at least two languages (for the route alias prefix)
        $this->assertTrue($this->siteService->getCurrentSite()->languages()->count() >= 2);

        //Create a page translation with a page
        /** @var PageTranslation $pageTranslation */
        $pageTranslation = factory(PageTranslation::class)->create();
        $language = $pageTranslation->language()->first();

        /** @var Page $page */
        $page = $pageTranslation->translatable()->first();

        //Create another page translation with a page.
        //The translations name and slug are set to the ones of the first. The site_id will be different.
        //This results for pretty much the same route for both translations when handed over to the route service.
        //But their site_id's will differ.
        /** @var PageTranslation $pageTranslation_two */
        $pageTranslation_two = factory(PageTranslation::class)->make();
        $pageTranslation_two->name = $pageTranslation->name;
        $pageTranslation_two->slug = $pageTranslation->slug;
        $pageTranslation_two->save();

        $language_two = $pageTranslation->language()->first();

        //Make sure the second page has a different site
        /** @var Page $page_two */
        $otherSite = Site::where('id', '!=', $page->site_id)->first();
        $page_two = $pageTranslation_two->translatable()->first();
        $page_two->site()->associate($otherSite);
        $page_two->save();

        //Give the pages to the routeService. I now must create a route and link it to the pages.
        //The second page must have a different route and alias as the first one though the name and slug of the pages is the same
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page_two);

        //Get the route so we can check its attributes
        $routeOne = $pageTranslation->route()->first();
        $this->assertEquals(1, $pageTranslation->route()->count());

        $this->assertEquals('pages/'.$page->id, $routeOne->route);
        $this->assertEquals('/'.$language->iso_2.'/'.Str::slug($pageTranslation->name), $routeOne->alias);
        $this->assertEquals($pageTranslation->id, $routeOne->routable_id);
        $this->assertEquals(get_class($pageTranslation), $routeOne->routable_type);
        $this->assertEquals($this->siteService->getCurrentSite()->id, $routeOne->site_id);
        $this->assertEquals($language->id, $routeOne->language_id);

        //Get the route of the second translation so we can check its attributes.
        //It should be a bit different then the other route though the name and slug of the translations match. The site id's must be different
        $this->assertEquals(1, $pageTranslation_two->route()->count());
        $routeTwo = $pageTranslation_two->route()->first();

        $this->assertEquals('pages/'.$page_two->id, $routeTwo->route);
        $this->assertEquals('/'.$language_two->iso_2.'/'.Str::slug($pageTranslation_two->name), $routeTwo->alias);
        $this->assertEquals($pageTranslation_two->id, $routeTwo->routable_id);
        $this->assertEquals(get_class($pageTranslation), $routeTwo->routable_type);
        $this->assertEquals($otherSite->id, $routeTwo->site_id);
        $this->assertEquals($language_two->id, $routeTwo->language_id);

        $this->assertNotEquals($routeOne->site_id, $routeTwo->site_id);
    }

    /**
     * Some routes potentially have the same alias as another route. Because they MUST differ when they belong to
     * the same site, we test that they differ in a way that that the second and up routes are suffixed with a number.
     *
     * Example: Consider two pageTranslations which have exactly the same name and slug of "test". And they each belong
     * to a different Page. And both of those Pages have the same Site. And The site has 2 languages, Dutch and English.
     * In that case their route aliases must be different too. Because they are on the same site.
     * In this example the aliases must be: nl/test and nl/test-1
     *
     * @group RouteService
     * @test
     */
    public function routesForDifferentPageButSameTranslationValues()
    {
        $languagesCount = $this->siteService->getCurrentSite()->languages()->count();

        //Create a page translation with a page
        /** @var PageTranslation $pageTranslation */
        $pageTranslation = factory(PageTranslation::class)->create();
        $language = $pageTranslation->language()->first();

        /** @var Page $page */
        $page = $pageTranslation->translatable()->first();

        //Create another page translation with a page.
        //The translations name and slug are set to the ones of the first.
        //This results for pretty much the same route for both translations when handed over to the route service.
        //But it will suffix the latest route with a number.
        /** @var PageTranslation $pageTranslation_two */
        $pageTranslation_two = factory(PageTranslation::class)->make();
        $pageTranslation_two->name = $pageTranslation->name;
        $pageTranslation_two->slug = $pageTranslation->slug;
        $pageTranslation_two->save();

        $language_two = $pageTranslation->language()->first();

        /** @var Page $page_two */
        $page_two = $pageTranslation_two->translatable()->first();
        $page_two->site_id = $page->site_id;
        $page_two->save();

        //Give the pages to the routeService. I now must create a route and link it to the pages.
        //The second page must have a different route and alias as the first one though the name and slug of the pages is the same
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page);
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($page_two);

        //Get the route so we can check its attributes
        $routeOne = $pageTranslation->route()->first();
        $this->assertEquals(1, $pageTranslation->route()->count());

        $this->assertEquals('pages/'.$page->id, $routeOne->route);
        if($languagesCount >= 2) {
            $this->assertEquals('/'.$language->iso_2.'/'.Str::slug($pageTranslation->name), $routeOne->alias);
        } else {
            $this->assertEquals('/'.Str::slug($pageTranslation->name), $routeOne->alias);
        }
        $this->assertEquals($pageTranslation->id, $routeOne->routable_id);
        $this->assertEquals(get_class($pageTranslation), $routeOne->routable_type);
        $this->assertEquals($page->site_id, $routeOne->site_id);
        $this->assertEquals($language->id, $routeOne->language_id);

        //Get the route of the second translation so we can check its attributes.
        //It should be a bit different then the other route though the name and slug of the translations match
        $this->assertEquals(1, $pageTranslation_two->route()->count());
        $routeTwo = $pageTranslation_two->route()->first();

        $this->assertEquals('pages/'.$page_two->id, $routeTwo->route);
        //Notice the -1 suffix. This is because the alias without the suffix already is used in routeOne
        if($languagesCount >= 2) {
            $this->assertEquals('/'.$language_two->iso_2.'/'.Str::slug($pageTranslation_two->name).'-1', $routeTwo->alias);
        } else {
            $this->assertEquals('/'.Str::slug($pageTranslation_two->name).'-1', $routeTwo->alias);
        }
        $this->assertEquals($pageTranslation_two->id, $routeTwo->routable_id);
        $this->assertEquals(get_class($pageTranslation_two), $routeTwo->routable_type);
        $this->assertEquals($page_two->site_id, $routeTwo->site_id);
        $this->assertEquals($language->id, $routeTwo->language_id);
    }

    /**
     *  Consider this:
     *  a route for example has an alias of "services" and has a couple of subroutes with aliases "services/service-one"
     *  and "services/service-two". When the route with "services" as an alias is changed so that it's alias is "what-we-do",
     *  the aliasses of the subroutes must also be updated so that they are "what-we-do/service-one", "what-we-do/service-two"
     *
     *  The modification of subroutes when a parent route alias is edited, is tested in this method.
     *
     * @group RouteService
     * @test
     */
    public function testUpdatedAliasOfSubRoutesWhenParentIsChanged() {
        $languagesCount = $this->siteService->getCurrentSite()->languages()->count();

        //Create a pageModelService. We need this later on
        /** @var ModelServiceInterface $pageModelService */
        $pageModelService = app()->make(ModelServiceInterface::class);
        $pageModelService->setModelClassName(Page::class);

        //Create a root service page and two sub service pages
        /** @var PageTranslation $servicePageTranslation */
        $servicePageTranslation = factory(PageTranslation::class)->make();
        $servicePageTranslation->name = 'Services';
        $servicePageTranslation->slug = 'services';
        $servicePageTranslation->save();
        /** @var Page $servicePage */
        $servicePage = $servicePageTranslation->translatable()->first();
        $language = $servicePageTranslation->language()->first();


        /** @var PageTranslation $serviceOnePageTranslation */
        $serviceOnePageTranslation = factory(PageTranslation::class)->create();
        /** @var Page $serviceOnePage */
        $serviceOnePage = $serviceOnePageTranslation->translatable()->first();


        /** @var PageTranslation $serviceTwoPageTranslation */
        $serviceTwoPageTranslation = factory(PageTranslation::class)->create();
        /** @var Page $serviceTwoPage */
        $serviceTwoPage = $serviceTwoPageTranslation->translatable()->first();

        $serviceOnePage->makeLastChildOf($servicePage);
        $serviceTwoPage->makeLastChildOf($servicePage);

        //Generate routes for all the pages
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($servicePage);
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($serviceOnePage);
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($serviceTwoPage);


        //Modify the service page name and slug to "Things we do" and "things-we-do".
        $servicePageTranslation->name = "Things we do";
        $servicePageTranslation->slug = Str::slug($servicePageTranslation->name);
        $servicePageTranslation->save();

        //Update the route for the page. This now also should update the routes for the sub service pages.
        $this->routeService->createOrUpdateRoutesForModelsTranslationsIfChanged($servicePage);

        //Get the routes
        $servicePageRoute = $servicePageTranslation->route()->first();
        $serviceOnePageRoute = $serviceOnePageTranslation->route()->first();
        $serviceTwoPageRoute = $serviceTwoPageTranslation->route()->first();

        //Assert them
        if($languagesCount >= 2) {
            $this->assertEquals('/'.$language->iso_2.'/'.Str::slug($servicePageTranslation->name), $servicePageRoute->alias);
        } else {
            $this->assertEquals('/'.Str::slug($servicePageTranslation->name), $servicePageRoute->alias);
        }
        $this->assertEquals($servicePageRoute->alias.'/'.Str::slug($serviceOnePageTranslation->name), $serviceOnePageRoute->alias);
        $this->assertEquals($servicePageRoute->alias.'/'.Str::slug($serviceTwoPageTranslation->name), $serviceTwoPageRoute->alias);

        //Delete the parent page and check that the other sub pages are delete to and don't have routes
        $routeTable = (new Route)->getTable();
        $servicePageRouteAlias = $servicePageRoute->alias;
        $servicePageOneRouteAlias = $serviceOnePageRoute->alias;
        $servicePageTwoRouteAlias = $serviceTwoPageRoute->alias;

        $this->routeService->destroyForModel($servicePage);
        $this->translationService->destroyForModel($servicePage);
        $pageModelService->destroyForModel($servicePage);
        $servicePage->delete();
        $serviceOnePage->delete();
        $serviceTwoPage->delete();

        $this->assertNull($servicePage->fresh());
        $this->assertNull($serviceOnePage->fresh());
        $this->assertNull($serviceTwoPage->fresh());
        $this->assertDatabaseMissing($routeTable, ['alias' => $servicePageRouteAlias]);
        $this->assertDatabaseMissing($routeTable, ['alias' => $servicePageOneRouteAlias]);
        $this->assertDatabaseMissing($routeTable, ['alias' => $servicePageTwoRouteAlias]);
    }

}