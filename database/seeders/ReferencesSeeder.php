<?php
namespace Database\Seeders;

use Komma\KMS\Globalization\Languages\Models\Language;
use App\References\Models\Reference;
use App\References\Models\ReferenceTranslation;
use App\Site\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ReferencesSeeder extends Seeder
{
    /**
     * Run the seed
     */
    public function run()
    {
        //Get the languages
        $languages = Language::whereIn('iso_2', ['nl', 'en'])->get(['id', 'iso_2']);

        //Create the root page
        $edsReference          = $this->createEdsReference($languages);
        $harriesReference      = $this->createHarriesReference($languages);
        $eddiesReference       = $this->createEddiesReference($languages);
    }

    /**
     * @param Reference $rootReference
     * @param Site $site
     * @param Collection $languages
     * @return Reference
     */
    private function createEdsReference(Collection $languages): Reference
    {
        //Create the service itself...
        $newsPost = new Reference([
            'active'      => 1,
            'name'        => 'Ed de Goeij',
        ]);

        $newsPost->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $newsPostTranslation = new ReferenceTranslation([
            'title'               => 'Ed de Goeij',
            'subtitle'            => 'Oud-keeper - Nederlands elftal',
            'quote'               => 'Phasellus a est. Donec orci lectus, aliquam ut, faucibus non, euismod id, nulla. Praesent congue erat at massa. Mauris turpis nunc, blandit et, volutpat molestie, porta ut, ligula. Etiam sollicitudin, ipsum eu pulvinar rutrum, tellus ipsum laoreet sapien, quis venenatis ante odio sit amet eros. In hac habitasse platea dictumst. Nullam nulla eros, ultricies sit amet, nonummy id, imperdiet feugiat, pede.Fusce ac felis sit amet ligula pharetra condimentum. Phasellus gravida semper nisi. Quisque id odio.',
            'url'                 => 'http://knvb.nl',
        ]);
        $newsPostTranslation->language()->associate($language);
        $newsPostTranslation->translatable()->associate($newsPost);
        $newsPostTranslation->save();

        return $newsPost;
    }

    /**
     * @param Reference $rootReference
     * @param Site $site
     * @param Collection $languages
     * @return Reference
     */
    private function createHarriesReference(Collection $languages): Reference
    {
        //Create the service itself...
        $newsPost = new Reference([
            'active'      => 1,
            'name'        => 'Harrie van de Graaf',
        ]);

        $newsPost->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $newsPostTranslation = new ReferenceTranslation([
            'title'               => 'Harrie van de Graaf',
            'subtitle'            => 'CEO - Pannenkoek & Co',
            'quote'               => 'Geweldig mooie club',
            'url'                 => '',
        ]);
        $newsPostTranslation->language()->associate($language);
        $newsPostTranslation->translatable()->associate($newsPost);
        $newsPostTranslation->save();

        return $newsPost;
    }

    /**
     * @param Reference $rootReference
     * @param Site $site
     * @param Collection $languages
     * @return Reference
     */
    private function createEddiesReference(Collection $languages): Reference
    {
        //Create the service itself...
        $newsPost = new Reference([
            'active'      => 1,
            'name'        => 'Eddie Grillworst',
        ]);

        $newsPost->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $newsPostTranslation = new ReferenceTranslation([
            'title'               => 'Eddie Grillworst',
            'subtitle'            => 'Grillmeister - Café het velletje',
            'quote'               => 'Smyt dyn fleis mar op de Smoker, bie den zitse kei goe.',
            'url'                 => '',
        ]);
        $newsPostTranslation->language()->associate($language);
        $newsPostTranslation->translatable()->associate($newsPost);
        $newsPostTranslation->save();

        return $newsPost;
    }
}









