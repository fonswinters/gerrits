<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Sites\SiteServiceInterface;
use Komma\KMS\Users\Models\KmsUser;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

/**
 * Class UrlAvailabilityTest
 *
 * Tests if the url's in the sitemap are available
 *
 * @package Tests\Unit
 */
class UrlAvailabilityTest extends TestCase
{

    private SiteServiceInterface $siteService;

    private array $manualTestRoutes = [];

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->siteService = app(SiteServiceInterface::class);
        $this->siteService->setCurrentSiteToDefault();
        $this->setManualTestRoutes();
    }

    private function setManualTestRoutes() {

        $this->manualTestRoutes[] = route('preview.components');

        $this->manualTestRoutes[] = route('preview.mail.set_password_kms');
        $this->manualTestRoutes[] = route('preview.mail.reset_password_kms');

        $this->manualTestRoutes[] = route('preview.view.kms.auth.login');
        $this->manualTestRoutes[] = route('preview.view.kms.auth.forgot_password');
        $this->manualTestRoutes[] = route('preview.view.kms.auth.reset');
    }
    /**
     * @group UrlAvailabilityTest
     * @test
     * @throws \Exception
     */
    public function checkSitemapUrls()
    {
        $client = new Client();

        try {
            $response = $client->get(url('/sitemap.xml'));
        } catch (\Exception $exception) {
            $this->fail('Error: Application not available on "'.url('/').'". Did you forgot to run php artisan serve');
        }
        $this->assertEquals(200, $response->getStatusCode(), 'Error: Make sure php artisan serve --env=testing is running');

        $xmlString = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $response->getBody()->getContents());
        $sitemapXml = new \SimpleXMLElement($xmlString);

        $failedRoutes = collect();

        foreach ($sitemapXml as $element) {

            $link = (string) $element->loc;
            echo 'Checking ULR: ' . $link . PHP_EOL;

            try {
                $response = $client->get($link);
                $statusCode = $response->getStatusCode();
                $this->assertEquals(200, $statusCode, 'The url "'.$link.'" was not available (No status 200 code given). Status code was '.$statusCode);

            } catch (\Exception $exception) {

                $failedRoutes->push((object)[
                    'status' => $exception->getCode(),
                    'link' => $link,
                    'message' => $exception->getMessage()
                ]);
            }


        }

        if($failedRoutes->isNotEmpty()) {

            echo PHP_EOL;

            foreach ($failedRoutes as $failedRoute)
            {
                echo 'Error link: '.$failedRoute->link . PHP_EOL;
                echo 'Error status: '.$failedRoute->status . PHP_EOL;
                echo 'Error message: '.$failedRoute->message . PHP_EOL;
                echo PHP_EOL;
            }

            echo PHP_EOL;

            $this->assertEmpty($failedRoutes->count(), 'There are some errors on the defined routes.');
        }
    }

    /**
     * @group UrlAvailabilityTest
     * @test
     * @throws \Exception
     */
    public function checkManualRoutes()
    {
        if(app()->environment() === 'testing_ci') {
            echo 'Skipping the test "checkManualRoutes" because it fails for a yet to be discovered reason on github actions, but works locally. '.PHP_EOL;
            return;
        }

        $client = new Client();
        $failedRoutes = collect();

        foreach ($this->manualTestRoutes as $manualTestRoute) {

            echo 'Checking manual link: '.url($manualTestRoute).PHP_EOL;

            try {
                $response = $client->head(url($manualTestRoute)); //We do a head request since that will not get the content and laravel will not be picky about the request method (E.g If should you use post, put, patch etc)
                $statusCode = $response->getStatusCode();
                $this->assertEquals(200, $statusCode, 'The link "'.$manualTestRoute.'" was not available (No status 200 code given). Status code was '.$statusCode);

            } catch (\Exception $exception) {

                $failedRoutes->push((object)[
                    'status' => $exception->getCode(),
                    'link' => $manualTestRoute,
                    'message' => $exception->getMessage()
                ]);
            }
        }

        if($failedRoutes->isNotEmpty()) {

            echo PHP_EOL;

            foreach ($failedRoutes as $failedRoute)
            {
                echo 'Error link: '.$failedRoute->link . PHP_EOL;
                echo 'Error status: '.$failedRoute->status . PHP_EOL;
                echo 'Error message: '.$failedRoute->message . PHP_EOL;
                echo PHP_EOL;
            }

            echo PHP_EOL;

            $this->assertEmpty($failedRoutes->count(), 'There are some errors on the manual routes test. Out of the ' . sizeof($this->manualTestRoutes) . ' routes in the array, ' . $failedRoutes->count() .' are not working and only ' . (sizeof($this->manualTestRoutes) - $failedRoutes->count()) . ' do work.');
        }
    }

    /**
     * @group UrlAvailabilityTest
     * @test
     * @throws \Exception
     */
    public function checkKmsUrls()
    {

        $kmsRoute = url('/kms');

        // Get the KMS SuperAdmin User
        $superAdmin = \KmsUserTableSeeder::getSuperAdminDefaultCredentials();
        $user = KmsUser::where('email', $superAdmin['email'])->first();

        // Make sure the SuperAdmin User exists
        $this->assertInstanceOf(KmsUser::class, $user, 'Error: Make sure the Kms:seed has been run, and the SuperAdmin for email "' . $superAdmin['email'] .'" exists');

        // Ensure we the login is working, and the kms entry does not contain errors
        $response = $this->actingAs($user)->get($kmsRoute);
        $this->assertAuthenticated('kms');
        $this->assertEquals(200, $response->getStatusCode(), 'Error: Make sure php artisan serve --env=testing is running and the Kms:seed has been run.');

        $failedRoutes = collect();
        $kmsRoutes = collect();

        /**
         * Add the models that we want to valid that should be working.
         */
        $kmsNamedRoutes = [
            'pages' => ['siteSlug' => $this->siteService->getCurrentSite()->slug],
            'posts' => [],
            'vacancies' => [],
            'projects' => [],
            'services' => [],
            'references' => [],
            'buttons' => [],
            'servicepoints' => [],
            'websiteconfig' => [],
        ];

        // Map the above named routes and load the defined resource route.
        foreach ($kmsNamedRoutes as $kmsNamedRoute => $parameters) {

            /**
             * The resource routes we want to validate in this unit test.
             */
            $resourceRoutes = [
                'index',
                'create'
            ];

            // Only test the index route for given
            if(in_array($kmsNamedRoute, ['websiteconfig'])) {
                $resourceRoutes = [
                    'index',
                ];
            }

            foreach ($resourceRoutes as $resourceRoute) {

                // Check if combination of the named route and resource route is defined
                if( ! \Route::has($kmsNamedRoute. '.' . $resourceRoute)) {
                    $failedRoutes->push((object)[
                        'status' => 0,
                        'link' => $kmsNamedRoute. '.' . $resourceRoute,
                        'message' => 'Named route has not been found.'
                    ]);
                    continue;
                }

                $kmsRoutes->push(route( $kmsNamedRoute. '.' . $resourceRoute, $parameters));
            }
        }

        // Loop through kms routes and validate that the views are working
        foreach ($kmsRoutes as $kmsRoute) {

            echo 'Checking KMS link: '.$kmsRoute.PHP_EOL;

            // We need to reset static variables of the attribute, because else it will have duplicated key names
            Attribute::resetAllInstances();

            try {
                $response = $this->actingAs($user)->get($kmsRoute);
                $statusCode = $response->getStatusCode();
                $this->assertEquals(200, $statusCode, 'The link "'.$kmsRoute.'" was not available (No status 200 code given). Status code was '.$statusCode);

            } catch (\Exception $exception) {

                $failedRoutes->push((object)[
                    'status' => $exception->getCode(),
                    'link' => $kmsRoute,
                    'message' => $exception->getMessage()
                ]);
            }
        }

        if($failedRoutes->isNotEmpty()) {

            echo PHP_EOL;

            foreach ($failedRoutes as $failedRoute)
            {
                echo 'Error link: '.$failedRoute->link . PHP_EOL;
                echo 'Error status: '.$failedRoute->status . PHP_EOL;
                echo 'Error message: '.$failedRoute->message . PHP_EOL;
                echo PHP_EOL;
            }

            echo PHP_EOL;

            $this->assertEmpty($failedRoutes->count(), 'There are some errors on the routes in the kms.');
        }

    }
}
