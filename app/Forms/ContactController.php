<?php

namespace App\Forms;

use App\Base\Controller;
use App\Http\Requests\ContactRequest;
use App\Pages\PageController;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

final class ContactController extends Controller
{
    /**
     * @var FormService
     */
    private $formService;

    /**
     * ContactController constructor.
     * @param FormService $formService
     */
    public function __construct(FormService $formService)
    {
        parent::__construct();

        $this->formService = $formService;
        $this->formService->setOrigin('contact');
    }

    /**
     * Store the request and send it by e-mail
     * Note: Validation is done in the request itself
     *
     * @param ContactRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(ContactRequest $request)
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
        // Remove token from request (this creates and array)
        Mail::send(new ContactMail($request->except('_token', '_willie', '_honey')));
        // Return json response with
        return response()->json([
            'redirectUrl' => localized_route('contact.success')
        ]);
    }

    /**
     * Show page where we thank the user
     *
     * @return mixed
     */
    public function success()
    {
        $pageController = new PageController();
        return $pageController->show($this->links->contact->node)->with('send', true);
    }
}