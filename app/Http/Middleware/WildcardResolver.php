<?php


namespace App\Http\Middleware;

use App\Http\Wildcards\WildcardInterface;
use App\Pages\Models\PageTranslation;
use App\Routes\Models\Route;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class WildcardResolver extends AbstractResolver
{

    private static $wildcardFileNamespace = 'App\Http\Wildcards\\';

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if route should be excluded from resolving
        if ($this->isExcludedFromResolving($request)) {
            return $next($request);
        }
        $this->log('trying to resolve uri: '.$request->path());

        // Check if route is already resolved
        if ($this->isRouteResolved($request)) {
            $this->log('Request already resolved. Ignoring it. WildcardResolver done.');
            return $next($request);
        }

        $wildcardRoute = $this->getWildcardRoute($request);

        //The request did not represent a wildcard route. So pass the request further into the application and return that.
        if (!$wildcardRoute) {
            $this->log('Request was not a wildcard. Ignoring it. WildcardResolver done.');
            return $next($request);
        }

        //Check if translation matches the set language else redefine it
        $language = $wildcardRoute->routable->language;
        if(\App::getLanguage() != $language) {
            $this->log('The application language is not the same as the page translation from the resolved route. Changing the application language to language with id: '.$language->id);
            \App::setLanguage($language);
        }

        $wildcardTail = $this->getWildcardTail($request, $wildcardRoute);
        $this->log('WildcardRoute tail: '.$wildcardTail);

        // Create wildcard instance for type
        $wildcardFQCN = $this->getWildcardFQCN($wildcardRoute->route); //The value of the route for example is "products".
        /** @var WildcardInterface $wildcardInstance */
        $wildcardInstance = new $wildcardFQCN;

        //Duplicate the request so we can generate an restful Request
        $modifiedRequest = $request->duplicate();

        // Let the wildcard instance handle the rest of resolving
        $modifiedRequest = $wildcardInstance->handle($modifiedRequest, $wildcardRoute, $wildcardTail);

        // If the wildcardInstance didn't modify the request return it
        if($modifiedRequest->path() === $request->path()) {
            $this->log($wildcardFQCN.' did not modified the request so proceed to the next resolver');
            return $next($request);
        }

        // Append original path, else have to done some ugly shit to grab it again
        $modifiedRequest->attributes->add([
            'original_path' => $request->url()
        ]);

        $this->log($wildcardFQCN.' Modified the request so that it\'s url became: '.$modifiedRequest->getUri());

        //Set the resolved key on the request to tell other route solving things that they don't need to resolve
        $modifiedRequest = $this->setRouteResolved($modifiedRequest);

        return $next($modifiedRequest);
    }

    /**
     * Use the request path (for example: /nl/producten/onderste-balk-plastic-aluminium) to find a wildcardRoute model
     * That matches most of the url. E.g: /nl/producten.
     * If it cannot be found, it will return null instead.
     *
     * @param Request $request
     * @return Route|null
     */
    private function getWildcardRoute(Request $request)
    {
        //request->segments() example value: [ 'nl', 'producten', 'onderste-balk-plastic-aluminium'] in an url of: http://localhost:8000/nl/producten/onderste-balk-plastic-aluminium
        $firstSegments = $request->segments();

        // The "not like" leaves out the route records which contain forward slashes. These are alias routes.
        $wildcardRoutes = Route::where('route', 'not like', '%/%')
            ->where('site_id', $this->siteService->getCurrentSite()->id)
            ->get();

        $wildcardRoute = null;

        //Pop the last element from the firstSegments array and put it in the currentSegment variable. Shortening $firstSegments by one. Use firstSegments array to find a wildcard route.
        while ($currentSegment = array_pop($firstSegments) && $wildcardRoute == null) {

            //First loop iteration example of value of firstSegments: ['nl', 'producten']
            $route = '/' . implode('/', $firstSegments); //First loop iteration example value of route: /nl/producten

            $this->log('Checking if there is a route with a route value of: "'.$route.'"');
            if($route == '/') continue; // The route / never is a wildcard. So continue to a next iteration.

            //Check if the route can be found. If not and if we still have firstSegments, run the next loop iteration.
            $wildcardRoute = $wildcardRoutes->where('alias', '=', $route)->first(); //The "not like" leaves out the route records which contain forward slashes. These are alias routes.
        }

        if($wildcardRoute) $this->log('Wildcard Route found for route: "'.$wildcardRoute->route.'". Id: "'.$wildcardRoute->id.'".');
        else $this->log('No wildcard route found.');

        return $wildcardRoute;
    }

    /**
     * Strips the wildcardRoute's route from the request. Whats left over is the tail.
     * and that's what is going to be returned.
     *
     * Example: Consider that the request path is: /nl/producten/onderste-balk-plastic-aluminium
     * And that the wildcardRoute's route is /nl/producten.
     * The tail part that is going to be returned will be onderste-balk-plastic-aluminium.
     *
     * @param Request $request
     * @param Route $wildcardRoute
     * @return string
     */
    private function getWildcardTail(Request $request, Route $wildcardRoute): string
    {
        return str_replace($wildcardRoute->alias.'/', '', '/'.$request->path());
    }



    /**
     * Gets the fully qualified class name from a wild card name.
     * For example when you pass 'Products' it will return 'App\Http\Wildcards\Products'
     *
     * @param string $wildcardClass
     * @return string
     */
    private function getWildcardFQCN(string $wildcardClass):string
    {

        $wildcardFQCN = static::$wildcardFileNamespace . ucfirst(strtolower($wildcardClass)) . 'Wildcard';

        if(!self::wildcardExists($wildcardFQCN)) {
            throw new \InvalidArgumentException($wildcardFQCN." does not exist or it wasn't an implementation of ".WildcardInterface::class);
        }

        return $wildcardFQCN;
    }

    /**
     * Checks if a given name can be resolved to a FQCN in the PSR-4 wildcardFileNamespace path.
     * Returns true if it exist and implements the WildcardInterface. false if not.
     *
     * @param string $wildcardFQCN
     * @return bool
     */
    public static function wildcardExists(string $wildcardFQCN) {

        if (!class_exists($wildcardFQCN) || !is_a($wildcardFQCN, WildcardInterface::class, true)) {
            return false;
        }
        return true;
    }

    /**
     * @param $codeName
     * @return Route|null
     */
    public static function getWildCardIndexRouteForPageWithCodeName($codeName)
    {
        $currentLanguage = app()->getLanguage();
        $productPageTranslationForCurrentLanguage = PageTranslation::whereHas('translatable', function(Builder $query) use($codeName) {
            $query->where('code_name', '=', $codeName);
        })->where('language_id', '=', $currentLanguage->id)->first();

        if(!$productPageTranslationForCurrentLanguage) return null;

        $productWildcardIndexRoute = Route::where('alias', '=', '/'.$currentLanguage->iso_2.'/'.$productPageTranslationForCurrentLanguage->slug)->first();
        return $productWildcardIndexRoute;
    }

}