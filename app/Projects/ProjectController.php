<?php

namespace App\Projects;

use App\Base\Controller;
use App\Buttons\Models\Button;
use App\Buttons\Models\ButtonTranslation;
use App\Pages\Models\Page;
use App\Projects\Models\Project;
use App\Servicepoints\Models\Servicepoint;
use Komma\KMS\Components\ComponentService;

class ProjectController extends Controller
{
    private $projectService;

    public function __construct(ProjectService $projectService)
    {
        parent::__construct();
        $this->projectService = $projectService;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $page = $this->links->projects->node;

        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);

        $projects = $this->projectService->getAllProjects(true);
        $projects->withPath('/' . $this->links->projects->route);

        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($page->translation);

        $this->pageService->setSharableVariables($page->servicepoint_id, $page->servicepoint_button_id, $page->servicepoint_heading);

        // split the Hero images from the "normal" page images
        list($documents, $heroDocuments) = $page->translation->documents->partition(function ($document) {
            return $document->key == 'Documents-pages';
        });
        $page->documents = $documents;

        $heroButton = Button::where('id', $page->translation->hero_button_id)->with('translation')->first();

        $page->hero = (object)[
            'documents' => $heroDocuments,
            'active' => $page->translation->hero_active,
            'title' => $page->translation->hero_title,
            'description' => $page->translation->hero_description,
            'buttons' => !empty($heroButton) ? $heroButton : null,
        ];

        // Return view
        return view('templates.projects_index',[
            'page' => $page,
            'components' => $components,
            'projects' => $projects,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
        ]);
    }

    /**
     * @param Project $service
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Project $project)
    {
        $project->load('translation','translations');

        $projects = $this->projectService->getAllProjects();

        $page = $this->links->projects->node;
        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);
        $this->pageService->extendLanguageMenuWithResource($languageMenu, $project);

        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($project->translation);

        // split the Hero images from the "normal" post images
        list($documents, $heroDocuments) = $project->translation->documents->partition(function ($document) {
            return $document->key == 'Documents-projects';
        });
        $project->documents = $documents;

        // fill the page hero object
        $heroButton = Button::where('id', $project->translation->hero_button_id)->with('translation')->first();

        $project->hero = (object)[
            'documents' => $heroDocuments,
            'active' => $project->translation->hero_active,
            'title' => $project->translation->hero_title,
            'description' => $project->translation->hero_description,
            'buttons' => !empty($heroButton) ? $heroButton : null,
        ];

        $discover_more_page_codenames = $page->discoverPages()->get()->map(function(Page $page) {
            return $page->code_name;
        })->toArray();

        $viewData = [
            'page' => $page,
            'components' => $components,
            'projects' => $projects,
            'project' => $project,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
            'discover_page_codenames' => $discover_more_page_codenames,
        ];

        $this->pageService->setSharableVariables($project->servicepoint_id, $project->servicepoint_button_id, $project->servicepoint_heading);

        // Return view
        return view('templates.projects_show', $viewData);
    }

}