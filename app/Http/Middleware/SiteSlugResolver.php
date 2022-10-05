<?php


namespace App\Http\Middleware;


use Komma\KMS\Sites\SiteServiceInterface;

/**
 * Class FrontendSitesResolver
 *
 * Does set the current site depending on the host / domain name using the site config file.
 *
 * @package App\Http\Middleware
 */
class SiteSlugResolver
{
    /** @var SiteServiceInterface $siteService */
    private $siteService;

    /**
     * SlugSitesResolver constructor.
     * @param SiteServiceInterface $siteService.
     */
    public function __construct(SiteServiceInterface $siteService)
    {
        $this->siteService = $siteService;
    }

    /**
     * Set site on Application.k
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if(!$request->route()) throw new \RuntimeException('Could not get the route of the request. Perhaps you\'re using the middleware in global middleware instead of rout middleware. Route is never availble in global middleware due to architectural concerns');

        if(
            $request->segments()[0] !== 'kms' ||
            $request->path() == 'kms' ||
            $request->path() == 'kms/login' ||
            $request->path() == 'kms/logout'
        ) return $next($request); //This middleware only works for kms

        $siteSlug = $request->route('siteSlug');

        $this->siteService->setCurrentSiteBySlug($siteSlug);

        //Don't bother the rest of the application with the siteSlug parameter
        $request->route()->forgetParameter('siteSlug');

        //Return the Request, and check the other routes
        return $next($request);
    }
}