<?php

namespace App\Base;


use App\Pages\PageService;
use App\Routes\Models\Route;
use App\Site\Site;
use Illuminate\Support\Facades\Request;
use Komma\KMS\Sites\SiteServiceInterface;
use App\WebsiteConfig\Model\WebsiteConfig;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $pageService;
    protected $links;

    /** @var SiteServiceInterface */
    private $siteService;

    /** @var Site  */
    protected $site;

    public function __construct()
    {
        if(\App::runningInConsole()) return;

        $this->preventRestRouteFromWorking();
        $this->pageService = new PageService();
        $this->links = $this->pageService->getAllTranslatedPageRoutes();

        $this->siteService = app(SiteServiceInterface::class);
        $this->site = $this->siteService->getCurrentSite();
    }

    // TODO: Add this to the linker / Links class
    protected function keepTrackOfPagination(string $modelPaginationKey, $paginationKey = 'page')
    {
        // Create object for pagination
        $pagination = (object)[
            'key'  => $paginationKey,
            'page' => Request::get($paginationKey, 1),
        ];

        // Store and save session
        session([$modelPaginationKey => $pagination]);
        session()->save();
    }

    protected function createPreviousRoute(string $modelPaginationKey, string $route)
    {
        // If session is null or on the first page, we need the normal route
        if(!$pagination = session($modelPaginationKey, null)) return $route;
        if($pagination->page == 1) return $route;

        return $route . '?' .$pagination->key . '=' . $pagination->page;
    }

    /**
     * This method will prevent that REST routes are working with the application
     * They should be resolved by the middleware
     *
     */
    private function preventRestRouteFromWorking(){
        if (\App::runningInConsole()) return;

        $request = request();

        // Normally it should be resolved or it's a hard defined route
        // Example. contact/process
        if(isset($request->resolved) && $request->resolved) return;
        else{
            // Get segments and path
            $path = \Request::path();
            $segments = \Request::segments();

            // Check if current path is a restfull path, or the first segment of it is
            $restRoutes = Route::whereIn('route', [$path, $segments[0]])
                ->get();

            // Rest routes should be empty, or it already should be resolved
            if($restRoutes->count() == 0) return;
            else{
                // If production throw 404
                if(\App::environment() == 'production') throw abort(404);

                // Else Argument Exception with explanation
                else throw new \InvalidArgumentException("Route path or the first segment is listed as an REST route: " . $path);
            }
        }
    }
}
