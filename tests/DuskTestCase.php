<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
         static::startChromeDriver();
    }

    /**
     * Make sure all cookies (and thus session) are cleared after each test
     */
    public function tearDown(): void
    {
        foreach (static::$browsers as $browser) {
            $browser->driver->manage()->deleteAllCookies();
        }
        parent::tearDown();
    }

    /**
     * Register the base URL with Dusk.
     *
     * @return void
     * @throws \Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->browse(function (Browser $browser) {
            $browser->resize(1920, 1080);
        });
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
//            '--headless'
        ]);

        // To solve the chrome version bug: php artisan dusk:chrome-driver 74
        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        ));
    }
}
