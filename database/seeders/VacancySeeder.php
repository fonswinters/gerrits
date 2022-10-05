<?php
namespace Database\Seeders;

use App\Vacancies\Models\Vacancy;
use App\Vacancies\Models\VacancyTranslation;
use Illuminate\Support\Str;
use Komma\KMS\Globalization\Languages\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Faker\Generator;

class VacancySeeder extends Seeder
{
    private Generator $faker;
    private Generator $fakerNL;

    private int $vacancyCount = 5;
    private array $studyLevels = [
        'Bachelor',
        'Master',
        'Associate',
    ];

    /**
     * Run the seed
     */
    public function run()
    {
        $this->faker = \Faker\Factory::create();
        $this->fakerNL = \Faker\Factory::create('nl_NL');
        //Get the languages
        $languages = Language::whereIn('iso_2', ['nl', 'en'])->get(['id', 'iso_2']);

        //Create the root page
        $siteRootVacancy = new Vacancy(['active' => 0]);
        $siteRootVacancy->makeRoot();

        for($i = 0; $i < $this->vacancyCount; $i++) {
            $this->createRandomVacancy($siteRootVacancy, $languages);
        }
    }

    /**
     * @param Vacancy $siteRootVacancy
     * @param Collection $languages
     * @return Vacancy
     */
    private function createRandomVacancy(Vacancy $siteRootVacancy, Collection $languages): Vacancy
    {
        //Create the service itself...
        $vacancy = new Vacancy([
            'active'      => 1,
        ]);

        $vacancy->makeLastChildOf($siteRootVacancy);
        $vacancy->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $jobTitle = $this->fakerNL->unique()->jobTitle;


        $vacancyTranslation = new VacancyTranslation([
            'slug'                => Str::slug($jobTitle),
            'name'                => ucfirst($jobTitle),
            'description'         => $this->faker->catchPhrase,
            'level'                => $this->faker->randomElement($this->studyLevels),
            'experience'           => $this->faker->randomElement(['1-4','2-5','3-6','4-8']) .' jaar ervaring',
            'salary'               => $this->faker->optional($weight = 0.8, 'Nader overeen te komen')->randomElement(['€950 - 1150','€1250 - 1500','€1500 - 1750','€1850 - 2400']),
            'hours'               => $this->faker->optional($weight = 0.8, 'Fulltime')->randomElement(['16-18 uur','20-24 uur','24-36 uur','32-36 uur']),
            'hero_active'         => 1,
            'hero_title'          => $this->faker->catchPhrase,
            'hero_description'    => $this->fakerNL->paragraph(7),
        ]);
        $vacancyTranslation->language()->associate($language);
        $vacancyTranslation->translatable()->associate($vacancy);
        $vacancyTranslation->save();

        return $vacancy;
    }

}









