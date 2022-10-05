<?php

namespace App\Vacancies;

use App\Base\Controller;
use App\Buttons\Models\Button;
use App\Forms\FormService;
use App\Http\Requests\VacancyRequest;
use App\Mail\VacancyMail;
use App\Pages\Models\Page;
use App\Servicepoints\Models\Servicepoint;
use App\Vacancies\Models\Vacancy;
use App\VacancyProcess\VacancyProcessService;
use App\WebsiteConfig\Model\WebsiteConfig;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Komma\KMS\Components\ComponentService;

class VacancyController extends Controller
{
    /**
     * @var FormService
     */
    private $formService;
    private $vacancyService;
    private $processService;

    public function __construct(VacancyService $vacancyService, FormService $formService, VacancyProcessService $processService)
    {
        parent::__construct();
        $this->vacancyService = $vacancyService;

        $this->formService = $formService;
        $this->formService->setOrigin('vacancy');

        $this->processService = $processService;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $page = $this->links->vacancies->node;

        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);

        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($page->translation);

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

        $this->pageService->setSharableVariables($page->servicepoint_id, $page->servicepoint_button_id, $page->servicepoint_heading);

        // Return view
        return view('templates.vacancies_index',[
            'page' => $page,
            'components' => $components,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
        ]);
    }

    /**
     * Store the request and send it by e-mail
     * Note: Validation is done in the request itself
     *
     * @param VacancyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(VacancyRequest $request)
    {
        // Check for secret code
        if ($request->input('_willie') !== 'wonka') {
            return response()->json([
                'errors' => [
                    '_secretCode' => [
                        __('validation.secretCode')
                    ],
                ],
            ], 422);
        }



        // Store request in the database
        $this->formService->storeRequest($request);


        if($request->hasFile('files'))
        {
            $mimetypes = [
                'image/*' => 'Afbeelding' ,
                'application/pdf' => 'PDF',
                'application/msword' => 'Word 97-2004-document',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Word-document',
                'application/vnd.oasis.opendocument.text' => 'OpenOffice / LibreOffice',
            ];

            $validator = Validator::make($request->all(), [
                'files.*' => 'file|max:10240|mimetypes:'.implode(',', array_keys($mimetypes)), // 10MB (megabyte)
            ], [
                'max' => __('vacancy.validation.max_file_upload', ['size' => '10 MB']),
                'mimetypes' => __('vacancy.validation.mimetypes', ['types' => implode(', ', array_values($mimetypes))])
            ]);

            $validator->validate();
        }

        $vacancy = Vacancy::with('servicepoint.translation')->find($request->get('vacancy_id'));
        if(!$vacancy) abort(Response::HTTP_NOT_FOUND, 'Vacancy ID not found');

        // Remove token from request (this creates and array)
        Mail::send(new VacancyMail($request->except('_token', '_willie', '_honey', 'files'), $request->file('files'), $vacancy));

        return response()->json([
            'redirectUrl' => localized_route('vacancy.success', ['vacancy' => (string)$vacancy->id])
        ]);
    }

    /**
     * @param Vacancy $vacancy
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Vacancy $vacancy)
    {
        $vacancy->load('translation','translations');

        $vacancies = $this->vacancyService->getAllVacancies();

        $page = $this->links->vacancies->node;
        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);
        $this->pageService->extendLanguageMenuWithResource($languageMenu, $vacancy);

        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($vacancy->translation);

        // split the Hero images from the "normal" post images
        list($documents, $heroDocuments) = $vacancy->translation->documents->partition(function ($document) {
            return $document->key == 'Documents-vacancies';
        });
        $vacancy->documents = $documents;

        // fill the page hero object
        $heroButton = Button::where('id', $vacancy->translation->hero_button_id)->with('translation')->first();

        $vacancy->hero = (object)[
            'documents' => $heroDocuments,
            'active' => $vacancy->translation->hero_active,
            'title' => $vacancy->translation->hero_title,
            'description' => $vacancy->translation->hero_description,
            'buttons' => !empty($heroButton) ? $heroButton : null,
        ];

        $discover_more_page_codenames = $page->discoverPages()->get()->map(function(Page $page) {
            return $page->code_name;
        })->toArray();

//        $showServicePoint = Servicepoint::find( !empty($vacancy->servicepoint_id) ? $vacancy->servicepoint_id : config('site.global_servicePoint_id'));

        $viewData = [
//            'showServicePoint' => $showServicePoint,
            'page' => $page,
            'components' => $components,
            'vacancies' => $vacancies,
            'vacancy' => $vacancy,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
            'discover_page_codenames' => $discover_more_page_codenames,
        ];

        $this->pageService->setSharableVariables($vacancy->servicepoint_id, $vacancy->servicepoint_button_id, $vacancy->servicepoint_heading);

        // Return view
        return view('templates.vacancies_show', $viewData);
    }

    /**
     * Show page where we thank the user
     *
     * @return mixed
     */
    public function success()
    {

        /** @var Vacancy $vacancy */
        $vacancy = request()->route('vacancy');

        $vacancy->load('servicepoint');
        $servicePoint = $vacancy->servicepoint;

        $page = $this->links->vacancies->node;

        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);

        if(!$servicePoint) {
            $globalServicepointConfig = WebsiteConfig::where('code_name', '=', 'global_servicePoint_id')->first();

            if($globalServicepointConfig) {
                $servicePoint = Servicepoint::find($globalServicepointConfig->value);
            }
        }

        $vacancyProcess = $this->processService->getAllVacancyProcesses();
//        $vacancyProcess = $this->processService->models()->get();

        return view('templates.vacancies_thanks', [
            'links' => $this->links,
            'vacancy' => $vacancy,
            'servicePoint' => $servicePoint,
            'vacancyProcess' => $vacancyProcess,
            'languageMenu' => $languageMenu,
        ]);
//        $pageController = new PageController();
//        $view = $pageController->show($this->links->vacancies->node)->with('send', true);
//        dd($view, $this->links->vacancies->node);
//        return  $view;

//        return redirect()->back()->with('send',true);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function filters()
    {
        $filters = request('filters');
        dd('customize filter function in VacancyController');

//        if (sizeof($filters) != 1) {
//            throw abort(404);
//        }
//        $categoryName = $filters[0];
//
//        // Check if category exist else trow 404
//        if ( ! $category = VacancyCategoryTranslation::where('slug', $categoryName)
//            ->with('translatable')
//            ->first()) {
//            throw abort(404);
//        }
//
//        $page = $this->pageService->getPageByCodeName('blog');
//        $otherLanguageRoutes = $this->languageService->getOtherLanguagesRoutes($page);
//
//        //Get vacancies through the category
//        $vacancies = $category->translatable->vacancies()->paginate(8);
//        $vacancies->withPath('/' . $this->links->blog->route . '/' . $category->slug);
//
//
//        // Return view
//        return \View::make($this->baseViewPath.$this->pagePrefix.'index',[
//            'page' => $page,
//            'links' => $this->links,
//            'otherLanguages' => $otherLanguageRoutes,
//            'vacancies' => $vacancies,
//        ]);
    }




}