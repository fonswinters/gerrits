<?php

namespace App\Pages;

use App\Base\Controller;
use App\Buttons\Models\Button;
use App\Pages\Models\Page;
use App\WebsiteConfig\Model\WebsiteConfig;
use Komma\KMS\Components\ComponentService;

final class PageController extends Controller
{
    /**
     * @param Page $page
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Page $page)
    {
        // Get the page through the set links if translation isnt defined
        if(!isset($page->translation)) $page = $this->links->{$page->code_name}->node;

        /** @var ComponentService $componentService */
        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($page->translation);

        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);

        $view = 'templates.' . $page->code_name;
        if( ! view()->exists($view)) $view = 'templates.default';

        // split the Hero images from the "normal" page images
        list($documents, $heroDocuments) = $page->translation->documents->partition(function ($document) {
            return $document->key == 'Documents-pages';
        });
        $page->documents = $documents;

        // fill the page hero object
        $heroButton = Button::where('id', $page->translation->hero_button_id)->with('translation')->first();
        $page->hero = (object)[
            'documents' => $heroDocuments,
            'active' => $page->translation->hero_active,
            'title' => $page->translation->hero_title,
            'description' => $page->translation->hero_description,
            'buttons' => !empty($heroButton) ? $heroButton : null,
        ];

        // fill the heroImage object (currently only used on the homepage
        $homeHeroImages = WebsiteConfig::where('code_name', '=', 'home_hero_images')->first();
        $homeHeroTitle = WebsiteConfig::where('code_name', '=', 'home_hero_title')->first();
        $page->heroImage = (object)[
            'documents' => collect(),
            'caption' => ''
        ];
        if($homeHeroImages) {
            $page->heroImage = (object)[
                'documents' => $homeHeroImages->documents,
                'caption' => $homeHeroTitle->value
            ];
        }

        $homeHeroVideoConfig = WebsiteConfig::where('code_name', '=', 'home_hero_video')->first();
        if(isset($homeHeroVideoConfig)) {
            list($homeHeroAutoplay, $homeHeroUrl) = explode(',',$homeHeroVideoConfig->value);
            $page->heroVideo = (object)[
                'url' => $homeHeroVideoConfig ? $homeHeroUrl : '',
                'autoplay' => $homeHeroVideoConfig ? (bool)$homeHeroAutoplay : true,
            ];
        }

        $this->pageService->setSharableVariables($page->servicepoint_id, $page->servicepoint_button_id, $page->servicepoint_heading);

        //Get the "discover more" page code names.
        $discover_more_page_codenames = $page->discoverPages()->get()->map(function(Page $page) {
            return $page->code_name;
        })->toArray();

        // Return view
        return view($view,[
            'components' => $components,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
            'discover_page_codenames' => $discover_more_page_codenames,
            'page' => $page,
        ]);
    }

    //TODO: These routes are temporary for development purposes
    public function styleguide() {
        return view('templates.styleguide');
    }
}