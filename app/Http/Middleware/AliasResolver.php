<?php

namespace App\Http\Middleware;


use App\Routes\Models\RedirectRoute;
use App\Routes\RouteService;
use Illuminate\Http\Request;
use Komma\KMS\Helpers\KommaHelpers;

final class AliasResolver extends AbstractResolver
{


    /** @var RouteService */
    private $routeService;

    public function __construct()
    {
        parent::__construct();
        $this->routeService = app(RouteService::class);
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Check if route should be excluded from resolving
        if($this->isExcludedFromResolving($request)) return $next($request);
        $this->log('trying to resolve uri: '.$request->path());

        /*
        |
        | Note:
        | We don't check if the request is already resolved.
        | Because this resolver should be the first one that possibly can resolved a route.
        |
        */

        //Check if we can find an activeRoute based on the alias
        $this->log('Looking up a route with alias: '.$request->getPathInfo().' and site with id: '.$this->siteService->getCurrentSite()->id);
        $route = $this->routeService->getRouteByAlias($request->getPathInfo(), $this->siteService->getCurrentSite());

        // If route isn't found, continue request
        if(!$route) {
            $this->log('No route found. Alias resolver done.');
            return $next($request);
        } else {
            $this->log('Found a '.KommaHelpers::getShortNameFromClass($route).' with id '.$route->id);
        }

        // Check if we have a redirect route
        if(is_a($route, RedirectRoute::class))
        {
            $regularRoute = ($route->routable()->first()->route()->first());
            $this->log('Found a redirect route, therefore redirecting the user right now to the following route. Then then the alias resolver is done. '.$regularRoute->alias);
            return redirect($regularRoute->alias, $route->redirect_code);
        }

        // Get the page translation of the found route
        $pageTranslation = $route->routable;

        // If route has no page translation, continue request (shouldn't be possible)
        if(!$pageTranslation) {
            $this->log('Route has no translation, Alias resolver done.');
            return $next($request);
        }

        //Check if page translation matches the set language else redefine it
        $language = $pageTranslation->language;
        if(\App::getLanguage() != $language) {
            $this->log('The application language is not the same as the page translation from the resolved route. Changing the application language to language with id: '.$language->id);
            \App::setLanguage($language);
        }

        //Duplicate the request so we can generate an restful Request
        $modifiedRequest = $request->duplicate();

        // Append page id to request, for grabbing the page when we have changed the rest route
        // Append original path, else have to done some ugly shit to grab it again
        $modifiedRequest->attributes->add([
            'page_id' => $pageTranslation->page_id,
            'original_path' => $request->url()
        ]);

        //Get the routeString form the route
        $route = $route->route;

        //Check if there is an queryString and add this to the route
        //if ($query = $request->getQueryString()) $route . '?' . $query; // Unnecessary because you could still get them by Input::get()

        //Set the request URI and the original path
        $modifiedRequest->server->set('REQUEST_URI', $route);
        $this->log('Resolved alias uri "'.$request->path().'" to route uri: "'.$modifiedRequest->path().'". Passing it further till it matches a hardcoded regular laravel route.');

        //Set the resolved key on the request to tell other route solving things that they don't need to resolve
        $modifiedRequest = $this->setRouteResolved($modifiedRequest);

        //Route resolver did resolve route. Pass the request to the next middleware.
        return $next($modifiedRequest);

    }
}