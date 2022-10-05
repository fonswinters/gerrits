<?php
namespace Database\Seeders;

use App\Vacancies\Models\Vacancy;
use App\Vacancies\Models\VacancyTranslation;
use App\VacancyProcess\Models\VacancyProcess;
use App\VacancyProcess\Models\VacancyProcessTranslation;
use Illuminate\Support\Str;
use Komma\KMS\Globalization\Languages\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Faker\Generator;

class VacancyProcessSeeder extends Seeder
{
    private Generator $faker;

    private array $steps = [
        [
            'name' => 'Gesprek 1',
            'description' => 'Omschrijving van 1e stap.',
        ],
        [
            'name' => 'Gesprek 2',
            'description' => 'Omschrijving van 2e stap.',
        ],
        [
            'name' => 'Gesprek 3',
            'description' => 'Omschrijving van 3e stap.',
        ],
        [
            'name' => 'Contract tekenen',
            'description' => 'Omschrijving van de contract tekenen stap.',
        ],
    ];

    /**
     * Run the seed
     */
    public function run()
    {
        $this->faker = \Faker\Factory::create();

        //Get the languages
        $languages = Language::whereIn('iso_2', ['nl', 'en'])->get(['id', 'iso_2']);

        //Create the root page
        $siteRootVacancyProcess = new VacancyProcess();
        $siteRootVacancyProcess->makeRoot();

        foreach($this->steps as $step) {
            $this->createRandomVacancy($siteRootVacancyProcess, $languages, $step);
        }
    }

    /**
     * @param VacancyProcess $siteRootVacancyProcess
     * @param Collection $languages
     * @param $step
     * @return VacancyProcess
     */
    private function createRandomVacancy(VacancyProcess $siteRootVacancyProcess, Collection $languages, $step): VacancyProcess
    {
        //Create the service itself...
        $vacancyProcess = new VacancyProcess();

        $vacancyProcess->makeLastChildOf($siteRootVacancyProcess);
        $vacancyProcess->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();

        $vacancyProcessTranslation = new VacancyProcessTranslation([
            'name'                => $step["name"],
            'description'         => $step["description"].' '. $this->faker->paragraphs(1, true),
        ]);
        $vacancyProcessTranslation->language()->associate($language);
        $vacancyProcessTranslation->translatable()->associate($vacancyProcess);
        $vacancyProcessTranslation->save();

        return $vacancyProcess;
    }

}









