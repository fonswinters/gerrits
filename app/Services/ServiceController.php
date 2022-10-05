<?php

namespace App\Services;

use App\Base\Controller;
use App\Buttons\Models\Button;
use App\Pages\Models\Page;
use App\Servicepoints\Models\Servicepoint;
use App\Services\Models\Service;
use Komma\KMS\Components\ComponentService;

class ServiceController extends Controller
{
    private $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        parent::__construct();
        $this->serviceService = $serviceService;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $page = $this->links->services->node;

        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);

        $services = $this->serviceService->getAllServices(true);
        $services->withPath('/' . $this->links->services->route);

        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($page->translation);

        $this->pageService->setSharableVariables($page->servicepoint_id, $page->servicepoint_button_id, $page->servicepoint_heading);

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

        // Return view
        return view('templates.services_index',[
            'page' => $page,
            'components' => $components,
            'services' => $services,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
        ]);
    }

    /**
     * @param Service $service
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Service $service)
    {
        $service->load('translation','translations');

        $services = $this->serviceService->getAllServices();

        $page = $this->links->services->node;
        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);
        $this->pageService->extendLanguageMenuWithResource($languageMenu, $service);

        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($service->translation);

        // split the Hero images from the "normal" post images
        list($documents, $heroDocuments) = $service->translation->documents->partition(function ($document) {
            return $document->key == 'Documents-services';
        });
        $service->documents = $documents;

        // fill the page hero object
        $heroButton = Button::where('id', $service->translation->hero_button_id)->with('translation')->first();

        $service->hero = (object)[
            'documents' => $heroDocuments,
            'active' => $service->translation->hero_active,
            'title' => $service->translation->hero_title,
            'description' => $service->translation->hero_description,
            'buttons' => !empty($heroButton) ? $heroButton : null,
        ];

        $this->pageService->setSharableVariables($service->servicepoint_id, $service->servicepoint_button_id, $service->servicepoint_heading);

        //Get the "discover more" page code names.
        $discover_more_page_codenames = $page->discoverPages()->get()->map(function(Page $page) {
            return $page->code_name;
        })->toArray();

        // Return view
        return view('templates.services_show',[
            'page' => $page,
            'components' => $components,
            'services' => $services,
            'service' => $service,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
            'discover_page_codenames' => $discover_more_page_codenames,
        ]);
    }

}