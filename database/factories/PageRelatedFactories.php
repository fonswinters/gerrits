<?php
namespace Database\Factories;

use Komma\KMS\Core\ModelServiceInterface;
use Komma\KMS\Globalization\Languages\Models\Language;
use App\Pages\Models\Page;
use App\Pages\Models\PageTranslation;
use Komma\KMS\Sites\Models\Site;
use Komma\KMS\Sites\SiteServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(Page::class, function (Faker\Generator $faker) {
    $site = Site::first();

    return [
        'active' => 1,
        'site_id' => $site->id,
        'code_name' => '',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});

$factory->define(PageTranslation::class, function (Faker\Generator $faker) {
    /** @var SiteServiceInterface $siteServiceInterface */
    $siteServiceInterface = app(SiteServiceInterface::class);
    $siteServiceInterface->setCurrentSiteToDefault();

    $site = $siteServiceInterface->getCurrentSite();

    $language = Language::where('iso_2', '=', 'nl')->first();
    $name = $faker->word;

    /** @var ModelServiceInterface $pageModelService */
    $pageModelService = app()->make(ModelServiceInterface::class);
    $pageModelService->setModelClassName(Page::class);
    $rootPage = $pageModelService->getRootModelForTree();

    /** @var Page $page */
    $page = factory(Page::class)->make();
    $page->site()->associate($site);
    $page->makeLastChildOf($rootPage);

    return [
        'slug' => Str::slug($name),
        'name' => $name,
        'description' => $faker->paragraph,
        'meta_title' => $faker->word,
        'meta_description' => $faker->paragraph,
        'language_id' => $language->id,
        'page_id' => $page->id,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});