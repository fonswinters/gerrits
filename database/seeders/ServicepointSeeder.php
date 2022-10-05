<?php
namespace Database\Seeders;

use App\Servicepoints\Models\Servicepoint;
use App\Servicepoints\Models\ServicepointTranslation;
use App\Site\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ServicepointSeeder extends Seeder
{
    private \Faker\Generator $faker;
    private \Faker\Generator $fakerNL;
    private int $servicepointCount = 2;

    /**
     * Run the seed
     */
    public function run()
    {
        $this->faker = \Faker\Factory::create();
        $this->fakerNL = \Faker\Factory::create('nl_NL');

        $site = Site::where('slug', '=', 'default')->first();
        $languages = $site->languages()->get();  //Language::whereIn('iso_2', ['nl', 'en'])->get(['id', 'iso_2']);;

        for($i = 0; $i < $this->servicepointCount; $i++) {
            $this->createRandomServicepoint($languages);
        }
    }

    /**
     * @param Collection $languages
     * @return Servicepoint
     */
    private function createRandomServicepoint(Collection $languages): Servicepoint
    {

        $firstName = $this->fakerNL->firstName;
        $lastName = $this->fakerNL->lastName;
        $phoneNumber = $this->fakerNL->e164PhoneNumber;

        $servicePoint = new Servicepoint(
            ['name' => $firstName . ' ' . $lastName]
        );
        $servicePoint->save();


        //...the Dutch translation
        $language = $languages->where('id', '104')->first();

        if ($language) {
            $pageTranslation = new ServicepointTranslation([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'function' => $this->fakerNL->jobTitle,
                'telephone_label' => $phoneNumber,
                'telephone_url' => $phoneNumber,
                'email' => $this->faker->safeEmail,
            ]);
            $pageTranslation->language()->associate($language);
            $pageTranslation->translatable()->associate($servicePoint);
            $pageTranslation->save();
        }

        return $servicePoint;
    }

}










