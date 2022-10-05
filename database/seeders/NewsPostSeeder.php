<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Komma\KMS\Globalization\Languages\Models\Language;
use App\Posts\Models\Post;
use App\Posts\Models\PostTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Faker\Generator;

class NewsPostSeeder extends Seeder
{
    private Generator $faker;
    private Generator $fakerNL;
    private int $newsPostCount = 10;
    private array $newsPostList = [
        'Man bouwt vuilnisbak om tot racewagen en vestigt wereldrecord',
        'Gevaarlijke fazant terroriseert dorp.',
        'Belgische mijnensnuffelende rat krijgt gouden medaille voor dierlijke dapperheid en moed',
        'Britse dierentuin verplaatst papegaaien die constant bezoekers afkraken',
        'Australische pinguïnverzorgers laten gestrande pinguïn naar Pingu kijken',
        'Zeemeeuw wordt enorm boos op eigen spiegelbeeld',
        'Stadje bestrijdt zeeleeuwenplaag met opblaaspoppen',
        'Baby blijkt eindbaas in Street Fighter-game',
        'Gast bouwt mini-barbecue voor vleesetend plantje',
        'Tyrannosaurus teistert Thaise weggebruikers',
        'Knuffelkip in de war door nieuw kapsel'
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

        for($i = 0; $i < $this->newsPostCount; $i++) {
            $this->createRandomNewsPost($languages);
        }
    }

    /**
     * @param Collection $languages
     * @return Post
     */
    private function createRandomNewsPost(Collection $languages): Post
    {
        // Create a random date in the near future
        $date = Carbon::now();
        $date->addDays(mt_rand(10,90)*-1);

        //Create the service itself...
        $newsPost = new Post([
            'active'      => 1,
            'date'        => $date->toDateTimeString(),
        ]);

        $newsPost->save();

        $language = $languages->where('id', '104')->first();
        $name = $this->faker->unique()->randomElement($this->newsPostList);

        $newsPostTranslation = new PostTranslation([
            'slug'                => Str::slug($name),
            'name'                => ucfirst($name),
            'hero_title'          => $this->faker->catchPhrase,
            'hero_description'    => $this->faker->paragraphs(1, true),
        ]);

        $newsPostTranslation->language()->associate($language);
        $newsPostTranslation->translatable()->associate($newsPost);
        $newsPostTranslation->save();

        return $newsPost;
    }
}









