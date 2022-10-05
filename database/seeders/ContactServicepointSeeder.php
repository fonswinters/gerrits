<?php
namespace Database\Seeders;

use Komma\KMS\Globalization\Languages\Models\Language;
use App\Servicepoints\Models\Servicepoint;
use App\Servicepoints\Models\ServicepointTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ContactServicepointSeeder extends Seeder
{
    /**
     * Run the seed
     */
    public function run()
    {
        //Get the languages
        $languages = Language::whereIn('iso_2', ['nl', 'en'])->get(['id', 'iso_2']);

        //Create the root page
        $foodTruckNewsPost      = $this->createChefSlagerContactServicePoint($languages);
    }

    /**
     * @param Collection $languages
     * @return Servicepoint
     */
    private function createChefSlagerContactServicePoint(Collection $languages): Servicepoint
    {
        //Create the service itself...
        $contactServicePoint = new Servicepoint([
            'name'        => 'Chef Slager',
            'active'      => 1,
        ]);

        $contactServicePoint->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $servicePointTranslation = new ServicepointTranslation([
            'first_name'          => 'Chef',
            'last_name'           => 'Slager',
            'function'            => 'Dr. Butcher',
            'telephone_label'     => '+31 (0)6 12 34 56 78',
            'telephone_url'       => '+31612345678',
            'email'               => 'chef@demo-butcher.nl',
        ]);

        $servicePointTranslation->language()->associate($language);
        $servicePointTranslation->translatable()->associate($contactServicePoint);
        $servicePointTranslation->save();

        return $contactServicePoint;
    }
}









