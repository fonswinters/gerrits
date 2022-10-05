<?php
namespace Database\Seeders;

use App\Services\Models\Service;
use App\Services\Models\ServiceTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Komma\KMS\Globalization\Languages\Models\Language;
use Komma\KMS\Sites\Models\Site;

class ServiceSeeder extends Seeder
{
    /**
     * Run the seed
     */
    public function run()
    {
        //Get the languages
        $languages = Language::whereIn('iso_2', ['nl', 'en'])->get(['id', 'iso_2']);

        //Create the root page
        $siteRootService = new Service(['active' => 0]);
        $siteRootService->makeRoot();

        $campfireBBQService               = $this->createCampfireBBQService($siteRootService, $languages);
        $traditionalStreetfoodService     = $this->createTraditionalStreetFoodService($siteRootService, $languages);
        $southAfricanBraaiService         = $this->createSouthAfricanBraaiService($siteRootService, $languages);
    }


    /**
     * @param Service $rootService
     * @param Collection $languages
     * @return Service
     */
    private function createCampfireBBQService(Service $rootService, Collection $languages): Service
    {
        //Create the service itself...
        $service = new Service([
            'active'        => 1,
        ]);

        $service->makeLastChildOf($rootService);
        $service->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $serviceTranslation = new ServiceTranslation([
            'slug'                => 'campfire-bbq',
            'name'                => 'Campfire BBQ',
            'hero_title'          => 'Koken op open vuur',
            'hero_description'    => '<p>Bacon ipsum dolor amet jerky drumstick pig, tri-tip t-bone bacon pastrami turkey turducken tongue boudin fatback cupim pork chop pork. Capicola corned beef tongue, hamburger tail t-bone bacon strip steak buffalo pork belly pork loin kielbasa. Kevin ham hock spare ribs tri-tip. S</p>',
            'hero_active'         => 1,
        ]);
        $serviceTranslation->language()->associate($language);
        $serviceTranslation->translatable()->associate($service);
        $serviceTranslation->save();

        return $service;
    }

    /**
     * @param Service $rootService
     * @param Collection $languages
     * @return Service
     */
    private function createTraditionalStreetFoodService(Service $rootService, Collection $languages): Service
    {
        //Create the service itself...
        $service = new Service([
            'active'        => 1,
        ]);

        $service->makeLastChildOf($rootService);
        $service->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $serviceTranslation = new ServiceTranslation([
            'slug'                => 'traditional-street-food',
            'name'                => 'Traditional Street Food',
            'hero_title'          => 'Traditional Street Food',
            'hero_description'    => '<p>Nullam dictum felis eu pede mollis pretium. Sed aliquam ultrices mauris. Proin viverra, ligula sit amet ultrices semper, ligula arcu tristique sapien, a accumsan nisi mauris ac eros. Fusce neque. Nam ipsum risus, rutrum vitae, vestibulum eu, molestie vel, lacus.</p>',
            'hero_active'         => 1,
        ]);
        $serviceTranslation->language()->associate($language);
        $serviceTranslation->translatable()->associate($service);
        $serviceTranslation->save();

        return $service;
    }

    /**
     * @param Service $rootService
     * @param Collection $languages
     * @return Service
     */
    private function createSouthAfricanBraaiService(Service $rootService, Collection $languages): Service
    {
        //Create the service itself...
        $service = new Service([
            'active'        => 1,
        ]);

        $service->makeLastChildOf($rootService);
        $service->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $serviceTranslation = new ServiceTranslation([
            'slug'                => 'south-african-braai',
            'name'                => 'South African Braai',
            'hero_title'          => 'Wij gaan braai',
            'hero_description'    => '<p>Burgdoggen ham hock shoulder meatball short loin picanha tongue brisket pork chop chuck. Pork loin swine pastrami, frankfurter tri-tip bacon ham prosciutto filet mignon. Ball tip pork belly cow drumstick, picanha turkey pig shank biltong andouille porchetta brisket sirloin hamburger. Buffalo meatloaf tri-tip bacon kielbasa.</p>',
            'hero_active'         => 1,
        ]);
        $serviceTranslation->language()->associate($language);
        $serviceTranslation->translatable()->associate($service);
        $serviceTranslation->save();

        return $service;
    }
}









