<?php

namespace App\Events;

use App\Base\Controller;
use App\Events\Models\EventTranslation;
use App\Forms\FormService;
use App\Http\Requests\EventRequest;
use App\Events\Models\Event;
use App\Mail\EventSignUpReceivedMail;
use App\Mail\EventSignUpResponseMail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Komma\KMS\Components\ComponentService;

final class EventController extends Controller
{
    /**
     * @var FormService
     */
    private $formService;

    private $eventService;
    private $eventPaginationKey = 'eventPagination';

    public function __construct(EventService $eventService, FormService $formService)
    {
        parent::__construct();
        $this->eventService = $eventService;

        $this->formService = $formService;
        $this->formService->setOrigin('event');
    }

    /**
     * @return View
     */
    public function index()
    {
        $page = $this->links->events->node;

        $events = $this->eventService->getAllEvents();

        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);

        $this->pageService->setSharableVariables($page->servicepoint_id, $page->servicepoint_button_id, $page->servicepoint_heading);

        // Return view
        return view('templates.events_index',[
            'page' => $page,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
            'events' => $events,
        ]);
    }

    /**
     * @param Event $event
     * @return View
     */
    public function show(Event $event)
    {
        $event->load('translation','translations');
        $nextEvents = $this->eventService->getNextEvents($event);

        $page = $this->links->events->node;
        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);
        $this->pageService->extendLanguageMenuWithResource($languageMenu, $event);

        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($event->translation);

        // Create previous route for better navigation UX
        $previousRoute = $this->createPreviousRoute($this->eventPaginationKey, $this->links->events->route);

        $this->pageService->setSharableVariables($page->servicepoint_id, $page->servicepoint_button_id, $page->servicepoint_heading);

        // Return view
        return view('templates.events_show',[
            'page' => $page,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
            'components' => $components,
            'previousRoute' => $previousRoute,
            'event' => $event,
            'nextEvents' => $nextEvents,
        ]);
    }


    /**
     * Store the request and send it by e-mail
     * Note: Validation is done in the request itself
     *
     * @param EventRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(EventRequest $request)
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

        // TODO: With multiple language, determine which translation should be loaded
        $event = Event::where('id', $request->input('event'))
            ->where('active', 1)
            ->with('translation')
            ->first();

        if(!$event) return response()->json([
            'errors' => [
                'not_found' => [
                    __('validation.not_found', ['name' => __('events.event')])
                ],
            ],
        ], 404);

        // Remove token from request (this creates and array)
        Mail::send(new EventSignUpReceivedMail($request->except('_token', '_willie', '_honey', 'files', 'event'), $event));
        Mail::send(new EventSignUpResponseMail($request->except('_token', '_willie', '_honey', 'files', 'event'), $event));

        // Return json response with
        return response()->json([
            'redirectUrl' => localized_route('event.success', ['eventSlug' => $event->translation->slug])
        ]);
    }

    public function success($eventSlug) {

        $eventTranslation = EventTranslation::where('language_id', app()->getLanguage()->id)
            ->where('slug', $eventSlug)
            ->first();

        $event = Event::where('id', $eventTranslation->event_id)
            ->where('active', 1)
            ->first();

        if(!isset($event)) return abort(404);
        $event->setRelation('translation', $eventTranslation);

        return $this->show($event)
            ->with(['send' => true]);
    }

}