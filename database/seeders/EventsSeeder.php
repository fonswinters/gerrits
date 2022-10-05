<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Support\Str;
use Komma\KMS\Globalization\Languages\Models\Language;
use App\Events\Models\Event;
use App\Events\Models\EventTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class EventsSeeder extends Seeder
{
    private Generator $faker;
    private Generator $fakerNL;
    private int $eventCount = 6;
    private $eventList = [
        'Algemene leden vergadering',
        'Radiokampweek Jutberg',
        'Vlooienmarkt',
        'International lighthouse weekend',
        'Theater de Avenue',
        'Kerstborrel',
        'Computerbeurs CeBit',
        'Pannekoek-slinger wedstrijd',
        'Crazy 88',
        'Ouddorps bierfestival',
        'Winterfestivalconcert',
        'Rock on the kiosk',
        'Circus Vliet',
    ];
    /**
     * @var Generator
     */



    /**
     * Run the seed
     */
    public function run()
    {
        $this->faker = \Faker\Factory::create();
        $this->fakerNL = \Faker\Factory::create('nl_NL');
        //Get the languages
        $languages = Language::whereIn('iso_2', ['nl', 'en'])->get(['id', 'iso_2']);

        for($i = 0; $i < $this->eventCount; $i++) {
            $this->createRandomEvent($languages);
        }
    }


    /**
     * @param Collection $languages
     * @return Event
     */
    private function createRandomEvent(Collection $languages): Event
    {
        // Create a random date in the near future
        $datetime_start = Carbon::now();
        $datetime_start->addDays(mt_rand(50,130));

        //Create the service itself...
        $event = new Event([
            'active'      => 1,
            'datetime_start' => $datetime_start->toDateTimeString(),
            'datetime_end' => $datetime_start->addMinutes(mt_rand(60,480))->toDateTimeString(),
        ]);

        $event->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $name = $this->faker->unique()->randomElement($this->eventList);

        $eventTranslation = new EventTranslation([
            'slug'                => Str::slug($name),
            'name'                => ucfirst($name),
            'location'            => $this->fakerNL->optional($weight = 0.8)->city,
            'costs'               => $this->faker->optional($weight = 0.7, 'Toegang gratis')->randomElement(['€5,-','€7,50','€10,-','€14,99,-','€24,95','€32,50,-','€45,-']),
            'description'         => ucfirst($this->faker->optional($weight = 0.3)->catchPhrase),
        ]);
        $eventTranslation->language()->associate($language);
        $eventTranslation->translatable()->associate($event);
        $eventTranslation->save();

        return $event;
    }
}









