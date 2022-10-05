<?php


namespace App\Development;


use App\Site\Resources\Site as SiteResource;
use App\Site\Site;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Komma\KMS\Components\Component\Component;
use Komma\KMS\Documents\Models\Document;
use Komma\KMS\Documents\Resources\Document as DocumentResource;
use Komma\KMS\Globalization\Languages\Resources\Language;
use Komma\KMS\Sites\Kms\SiteService;
use Komma\KMS\Users\Models\KmsUser;
use Komma\KMS\Users\Resources\UserResource;

/**
 * Class TestApiController
 *
 * Used by tools like cypress to setup a end to end test.
 * Make sure the controller NEVER is accessible in production
 *
 * @package App\Development
 */
class TestApiController extends Controller
{
    public function __construct()
    {
        if(app()->environment() === 'production') {
            throw new \RuntimeException('It is unsafe to expose the TestApiController to production environments.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    */
    public function getComponents(Request $request) {
        $query = Component::query()->orderBy('created_at', 'desc');
        if($request->has('take')) $query->take($request->get('take'));
        $components = $query->get();
        return response()->json(['data' => $components]);
    }


    /*
    |--------------------------------------------------------------------------
    | KMS Users
    |--------------------------------------------------------------------------
    |
    | These functions act on users. Only for dev purposes
    |
    */
    /**
     * Create a random user and return it.
     */
    public function createKmsUser(Request $request)
    {
        /*** @var $user KmsUser */
        $user = factory(KmsUser::class)->create();

        if($request->has('attributes')) {
            $attributes = $request->get('attributes');
            $user->fill($attributes);
            if(isset($attributes['password'])) $user->password = Hash::make($attributes['password']);
            $user->save();
        }

        $userResource = new UserResource($user);
        return $userResource;
    }

    /**
     * Delete a kms user
     */
    public function deleteKmsUser(KmsUser $user)
    {
        $user = $user->delete();
        return response()->json(204);
    }

    /**
     * Show a random or specific
     */
    public function showKmsUser(KmsUser $user = null)
    {
        if(!$user) $user = KmsUser::inRandomOrder()->first();
        $userResource = new UserResource($user);
        return $userResource;
    }

    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    |
    | These functions act on documents. Only for dev purposes
    |
    */
    /**
     * List all documents
     */
    public function indexDocuments() {
        $documents = Document::latest()->with('documentable')->get();
        return DocumentResource::collection($documents);
    }

    /**
     * Delete a document
     */
    public function deleteDocument(Document $document)
    {
        $document = $document->delete();
        return response()->json(204);
    }

    /*
    |--------------------------------------------------------------------------
    | Sites
    |--------------------------------------------------------------------------
    |
    | These functions act on sites. Only for dev purposes
    |
    */
    /**
     * List all documents
     */
    public function indexSites() {
        $sites = Site::latest()->get();
        return SiteResource::collection($sites);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function currentSiteLanguages() {
        /** @var SiteService $siteService */
        $siteService = app(SiteService::class);
        $languages = $siteService->getSiteLanguages();
        return Language::collection($languages);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function languagesHavingSites() {
        $siteService = app(SiteService::class);
        $languages = $siteService->languagesHavingSites()->get();
        return Language::collection($languages);
    }

    /*
    |--------------------------------------------------------------------------
    | Mail intercepts
    |--------------------------------------------------------------------------
    |
    | These functions act on documents. Only for dev purposes
    */
    public function enableMailIntercepts()
    {
        session()->put('enable_mail_intercepts', true);
        return response(null, 204);
    }

    public function disableMailIntercepts()
    {
        session()->put('enable_mail_intercepts', false);
        return response(null, 204);
    }

    public function getInterceptedMails()
    {
        $response = response()->json(session()->pull('e2e_mails', []));
        session()->remove('e2e_mails');
        return $response;
    }
}