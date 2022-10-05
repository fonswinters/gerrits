<?php
namespace Database\Seeders;

use App\Buttons\Models\Button;
use App\Buttons\Models\ButtonTranslation;
use App\Site\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ButtonSeeder extends Seeder
{
    private $buttonList = [
        [
            'name' => 'Neem contact op',
            'url' => '/contact',
        ],
        [
            'name' => 'Wie zijn wij?',
            'url' => '/over-ons',
        ],
        [
            'name' => 'Bekijk onze diensten',
            'url' => '/diensten',
        ],
        [
            'name' => 'Ontdek onze projecten',
            'url' => '/projects',
        ],
        [
            'name' => 'Download algemene voorwaarden',
            'url' => '/algemene_voorwaarden.pdf',
        ],
        [
            'name' => 'Bekijk alle vacatures',
            'url' => '/vacatures',
        ]
    ];

    public function run()
    {
        //Get the default site
        /** @var Site $site */
        $site = Site::where('slug', '=', 'default')->first();

        $languages = $site->languages()->get();

        foreach($this->buttonList as $buttonData) {
            $this->createButton($languages, $buttonData);
        }
    }

    public function createButton(Collection $languages, $buttonData)
    {
        //Create the button itself...
        $button = new Button(['active' => 1, 'name' => $buttonData['name']]);
        $button->save();

        //...Create the dutch translation
        $url = $buttonData['url'] ?? '';

        //...Add "/nl" when multipleLanguages is true
        if(config('app.multipleLanguages')) {
            $url = '/nl'.$buttonData['url'];
        }
        $translation = new ButtonTranslation(['label' => $buttonData['name'], 'url' => $url]);
        $language = $languages->where('id', '104')->first();
        $translation->language()->associate($language);
        $translation->translatable()->associate($button);
        $translation->save();
    }
}