<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class LocalizedRoute
 *
 * Resolve the routes that are hard coded and translatable.
 * Those routes are stored in resources/lang/{iso_2}/site/routes.
 * We marked the resolved route with the name of the key in the file.
 *
 * For example:
 * 'site.password.request' => 'wachtwoord/vergeten',
 *
 * We named the actual route of the application route (which is 'password/reset') with 'site.password.request'.
 * When we got a request 'wachtwoord/vergeten' we do a reverse look up to get the name of the belonging route; 'site.password.request'.
 * Then we got the named route and return a modified request, which will got to 'password/reset'.
 *
 */
final class LocalizedRouteResolver extends AbstractResolver
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if route should be excluded from resolving
        if($this->isExcludedFromResolving($request)) return $next($request);

        // Check if route is already resolved
        if($this->isRouteResolved($request)) return $next($request);

        $this->log('Trying to resolve: '.$request->path());


        // Get the segments of the given route
        $segments = $request->segments();
        if(count($segments) == 0) return $next($request);

        // Check if we need to validate the first segment as language iso
        if(config('app.multipleLanguages')) {

            // Remove the language segment
            $languageIso = array_shift($segments);

            // If the site doesn't contain the language iso we return the request
            if(!$this->siteService->doesSiteContainsLanguageWhereIso($this->siteService->getCurrentSite(), $languageIso))
            {
                return $next($request);
            }

            //Change the app language to the one from the url
            if( app()->getLocale() != $languageIso) {
                $this->log('Changing the app language to: '.$languageIso);
                app()->changeLanguageByIso2($languageIso);
            }
        }

        //Check if the remaining route uri matches named route in the site routes translation file. If so, Change the url to the original named url and return that.
        $route = $this->getRestRouteFromLocalizedRoute($segments);

        // If route hasn't been resolved we return the request
        if(!$route){
            $this->log('Could not resolve: '.$request->path());
            return $next($request);
        }

        $translatedModifiedRequest = $request->duplicate();

        // Append original path, else have to done some ugly shit to grab it again
        $translatedModifiedRequest->attributes->add([
            'original_path' => $request->url()
        ]);

        $translatedModifiedRequest->server->set('REQUEST_URI', $route);

        $this->setRouteResolved($translatedModifiedRequest);

        return $next($translatedModifiedRequest);
    }

    /**
     * Get the rest route of the localized route
     *
     * For example:
     * wachtwoord/vergeten
     *
     * Or wildcard infused example:
     * wachtwoord/maken/123As4$as4125sdasjfisghvcda$2512
     *
     * @param  array  $routeSegments
     * @return string|null
     */
    private function getRestRouteFromLocalizedRoute(array $routeSegments)
    {
        // Get the translation routes and flipped it
        $translationRoutes = array_flip(__('routes'));

        // Get all the wildcard containing routes
        $wildcardContainingRoutes = Arr::where($translationRoutes, function ($value, $key) use ($translationRoutes) {
            if(Str::contains($key, ['{', '}'])) {
                return $key;
            }
        });

        // First try a simple look up
        $routeName = $this->findRouteNameByKeyInRouteSet(implode('/', $routeSegments), $translationRoutes);

        // Found, then we simply call the route
        if(isset($routeName)) {
            $route = route($routeName);
            $this->log('Found route "'.implode('/', $routeSegments).'" in the site/routes.php translation file. Resolving route using the name: "'.$routeName.'". Result: '.$route);
            return $route;
        }

        // Still to be resolved?
        // Then we have check or wildcard(s) containing routes
        foreach ($wildcardContainingRoutes as $translationWildcardRoute => $restRouteKey)
        {
            // Explode the segments of the translation route
            $translationRouteSegments = explode('/', $translationWildcardRoute);

            // If the sizes of the segments sets dont match we can already skip it
            if( sizeof($routeSegments) != sizeof($translationRouteSegments)) continue;

            // Swap the route segments with the parameters
            list($possibleTranslationRouteSegments, $usedWildcards) = $this->swapWildcardSegments($translationRouteSegments, $routeSegments);

            // Look up the glued route segments
            $routeName = $this->findRouteNameByKeyInRouteSet(implode('/', $possibleTranslationRouteSegments), $wildcardContainingRoutes);

            // Continue if route name isn't found
            if(!$routeName) continue;

            // We found the named route, yet we aren't done...
            // Because it has wildcard we need to get the parameters belonging to the named route
            // Luckily we already tracked all the used wildcards and they position in the segments
            $parameters = [];
            foreach ($usedWildcards as $segmentPosition => $usedWildcard) {

                // Then we store the used wildcard and grab the value from the original segments
                $parameters[$usedWildcard] = $routeSegments[$segmentPosition];
            }

            $parametersDump = null;
            foreach($parameters as $name => $value) !$parametersDump ? $parametersDump = $name.' => '.$value.' ': $parametersDump .= ', '.$name.' => '.$value;
            $this->log('Resolved route "'.$routeName.'" with parameters: ['.$parametersDump.']');
            $this->log('');

            // Now we can call our route
            return route($routeName, $parameters);
        }

        // If it hasn't been resolved already we don't think it's a localized route
        return null;
    }

    /**
     * Look up rest route by keys of the available translation routes
     *
     * @param  string  $routeKey
     * @param  array  $routeSet
     * @return mixed
     */
    private function findRouteNameByKeyInRouteSet(string $routeKey, array $routeSet)
    {
        if(isset($routeSet[$routeKey])) return $routeSet[$routeKey];
        return null;
    }

    /**
     * We swap the segments of the request
     * By their wildcard parameters for the look up
     * Note: we also track which parameters are swapped and what their position in the segments was
     *
     * @param  array  $translationRouteSegments
     * @param  array  $routeSegments
     * @return array
     */
    private function swapWildcardSegments(array $translationRouteSegments, array $routeSegments)
    {

        // We keep track of the used wildcard for when we possibly found the named route
        $usedWildcards = [];

        // Find the key(s) that are parameters
        foreach ($translationRouteSegments as $segmentPosition => $translationRouteSegment)
        {

            // Skip if not a parameters
            if( ! Str::contains($translationRouteSegment, ['{', '}'])) continue;

            // Store the wildcards by their position and remove the brackets
            $usedWildcards[$segmentPosition] =  str_replace(['{', '}'], '', $translationRouteSegment);

            // Else overwrite the segment on position
            $routeSegments[$segmentPosition] = $translationRouteSegment;
        }

        return [$routeSegments, $usedWildcards];
    }
}