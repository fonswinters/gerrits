<?php


namespace App\Development;

use App\Components\ComponentTypes;
use App\Events\Models\Event;
use App\Events\Models\EventTranslation;
use App\Forms\Models\Request;
use App\Pages\PageService;
use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Routing\Controller;
use Komma\KMS\Components\Component\ViewComponent;
use Komma\KMS\Globalization\Languages\Models\Language;
use Komma\KMS\Notifications\KmsSetPasswordNotification;
use Komma\KMS\Users\Models\KmsUser;
use Komma\KMS\Notifications\KmsResetPasswordNotification;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PreviewController extends Controller
{
    /**
     * @var \stdClass
     */
    private $links;
    private $pageService;

    public function __construct()
    {
        if(app()->runningInConsole()) return;
        $this->pageService = new PageService();
        $this->links = $this->pageService->getAllTranslatedPageRoutes();
    }

    /*
    |--------------------------------------------------------------------------
    | Mail previews
    |--------------------------------------------------------------------------
    |
    | These functions must returns views that represent mails.
    |
    */

    /**
     * @see KmsResetPasswordNotification
     * @return HtmlString|View
     */
    public function resetPasswordMailKms()
    {
        $user = factory(KmsUser::class)->make();
        $token = Str::random(32);
        return $this->notificationToView(new KmsResetPasswordNotification($user, $token));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @see SetPassword
     */
    public function setPasswordMailKms()
    {
        $customer = factory(KmsUser::class)->make();
        $token = Str::random(32);

        return $this->notificationToView(new KmsSetPasswordNotification($customer, $token));
    }

    public function loginViewKms()
    {
        return view('KMS::auth.login', ['links' => $this->links]);
    }

    public function forgotPasswordKms()
    {
        return view('KMS::auth.password.email', ['links' => $this->links]);
    }

    public function resetKms()
    {
        return view('KMS::auth.password.reset', ['token' => Str::random(32)]);
    }

    public function contact()
    {
        $fakeRequest = [
            'name' => 'Jan Doedel',
            'phone' => '012-34567890',
            'email' => 'jandoedel@komma.nl',
            'form_message' => 'Ik wil graag een afspraak maken voor een nieuwe webshop. Bellen heeft de voorkeur.',
        ];
        return view('emails.contact', ['request' => $fakeRequest]);
    }

    public function vacancyApply()
    {
        $fakeRequest = [
            'vacancy' => 'Professioneel worstelaar',
            'vacancy_id' => '7',
            'name' => 'Esther Ekster',
            'phone' => '098-76543210',
            'email' => 'esther.ekster@komma.nl',
            'motivation' => 'Ik zag deze vacature langskomen, en ik dacht meteen: Wauw, die baan is geknipt voor mij!',
        ];
        return view('emails.vacancy', ['request' => $fakeRequest]);
    }

    public function eventSignUpReceived()
    {
        return view('emails.eventSignUpReceived', [
            'subject' => __('email.eventSignUp.subject.received'),
            'event' => $this->fakeEvent(),
            'request' => [
                'name' => 'Barrie Bakker',
                'phone' => '011-2233440',
                'email' => 'barrie.bakker@komma.nl',
                'form_message' => 'Wat een leuk evenement. Ik ben zeker van de partij! Let\'s go!',
            ],
        ]);
    }

    public function eventSignUpResponse()
    {
        return view('emails.eventSignUpResponse', [
            'subject' => __('email.eventSignUp.subject.response') . ' ' . $this->fakeEvent()->translation->name,
            'event' => $this->fakeEvent(),
            'request' => [
                'name' => 'Patricia Paars',
                'phone' => '022-4466888',
                'email' => 'patricia.paars@komma.nl',
            ]
        ]);
    }

    /*
     |--------------------------------------------------------------------------
     | Helper methods
     |--------------------------------------------------------------------------
     |
     | These functions provide useful help for the ones that return views.
     */
    /**
     * Converts a notification to view
     *
     * @param Notification $notification
     * @return HtmlString|View
     */
    private function notificationToView(Notification $notification)
    {
        /** @var MailMessage $mail */
        $mail = $notification->toMail(\Auth::user());
        if($mail->markdown) {
            $markdown = new Markdown(view());
            return $markdown->render($mail->markdown, $mail->data());
        }
        elseif($mail->view)
        {
            return view($mail->view, $mail->data());
        } else {
            throw new \RuntimeException('Preview controller: I don\'t know how to render a view for "'.get_class($notification).'"');
        }
    }

    private function fakeEvent() {
        $event = new Event([
            'date' => Carbon::now(),
            'time' => '12.00u',
        ]);
        $event->id = 88;

        $eventTranslation = new EventTranslation([
            'name' => 'Pannekoek-slinger wedstrijd',
            'location' => 'Pannekoeken restaurant De Clown',
            'costs' => '€7,50',
            'description' => 'Schenkstroop verplicht',
        ]);

        $language = Language::where('iso_2', 'nl')->first();

        $eventTranslation->setRelations(['language' => $language, 'translatable' => $event]);
        $event->setRelations(['translation' => $eventTranslation]);

        return $event;
    }
}