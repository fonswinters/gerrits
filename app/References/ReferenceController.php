<?php

namespace App\References;

use App\Base\Controller;
use App\Pages\Models\Page;
use Komma\KMS\Components\ComponentService;

final class ReferenceController extends Controller
{
    private $referenceService;

    public function __construct(ReferenceService $referenceService)
    {
        parent::__construct();
        $this->referenceService = $referenceService;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function show()
    {
        $page = $this->links->references->node;
        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);


        $references = $this->referenceService->getAllReferences(true);
        $references->withPath('/' . $this->links->references->route);

        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($page->translation);

        $this->pageService->setSharableVariables($page->servicepoint_id, $page->servicepoint_button_id, $page->servicepoint_heading);

        //Get the "discover more" page code names.
        $discover_more_page_codenames = $page->discoverPages()->get()->map(function(Page $page) {
            return $page->code_name;
        })->toArray();

        // Return view
        return view('templates.references',[
            'page' => $page,
            'components' => $components,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
            'references' => $references,
            'discover_page_codenames' => $discover_more_page_codenames,
        ]);
    }

}