<?php


namespace App\Http\Middleware;

use Illuminate\Support\Str;

/**
 * Class SiteResolver
 *
 * Does set the current site depending on the host / domain name using the site config file
 *
 * @package App\Http\Middleware
 */
class SiteResolver extends AbstractResolver
{

    /**
     * Set site on Application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        // Check if route should be excluded from resolving
        if($this->isExcludedFromResolving($request)) return $next($request);

        $site = null;

        if(!config('app.multipleSites')){
            $this->siteService->setCurrentSiteToDefault();
            // If there is only one site, the redirect should be handled by the web.config
        }
        else{

            // Get the site slug through the domain binding in the config
            $siteSlug = $this->getCurrentSiteSlugFromDomain($request->root());

            // Check if domain isn't registered in the
            if(!$siteSlug) throw new \RuntimeException("SiteResolver: Domain '" . $request->root() . "' isn't registered in the domain config. So we can't bind it to a site.");

            // Set current site
            $this->siteService->setCurrentSiteBySlug($siteSlug);

            // Check if we need to redirect to the right secure main domain
            $redirectPath = $this->getHttpsRedirectPathIfNeeded($siteSlug, $request->root(), $request->path());
            if(!empty($redirectPath)) return redirect($redirectPath);
        }

        $site = $this->siteService->getCurrentSite();
        if(!$site->exists && !config('app.multipleSites')) throw new \RuntimeException("SiteResolver: This site doesn't exists. Make a new Site with this slug:'".$siteSlug."'  or rename the desired site to this slug.");

        // Check if defined language is one of the languages of this site
        // if not throw error, because it shouldn't happen...
        if(!$site->languages->contains(\App::getLanguage())){
            throw new \InvalidArgumentException("SiteResolver: Defined app language with iso_2 (".\App::getLanguage()->iso_2.") doesn't exist in this site (id: ".$site->id."), so check how this could happen.");
        }

        //Return the Request, and check the other routes
        return $next($request);

    }

    /**
     * Get the current site slug
     * Will be grabbed through the domain binding in the config
     *
     * @param string $root
     * @return int|mixed|string|null
     */
    private function getCurrentSiteSlugFromDomain(string $root)
    {
        $siteDomains = config('domains.' . \App::environment());
        $siteSlug = null;

        if(!$siteDomains) throw new \RuntimeException('SiteResolver: Please add the environment "'.\App::environment().'" to the domains config file');

        foreach ($siteDomains as $siteKey => $domains){
            foreach ($domains as $domain){
                if(Str::contains($root, $domain)){
                    if(!$siteSlug) $siteSlug = $siteKey;
                    else throw new \RuntimeException("SiteResolver: Domain isn't specific enough because active site key is overruled by other site, best practice is to define domains with there top-level domain in the domain config");
                }
            }
        }

        return $siteSlug;
    }

    /**
     * Get Https redirect path if needed
     *
     * @param  string  $siteSlug
     * @param  string  $root
     * @param  string  $path
     * @return string|null
     */
    private function getHttpsRedirectPathIfNeeded(string $siteSlug, string $root, string $path)
    {
        // If we aren't on production we don't want to redirect
        if(\App::environment() !== 'production') return null;

        // Check if the set site is on the main domain and has https
        $secureMainRoot = 'https://' . config('domains.' . \App::environment() . '.' . $siteSlug)[0];

        // return if we are already on the right https domain
        if($root === $secureMainRoot) return null;

        // If there is a path defined, append it to the redirect string
        if (isset($path) && ! in_array($path, ['', '/', 'index.php'])) {
            $secureMainRoot .= '/' . $path;
        }

        return $secureMainRoot;
    }


}