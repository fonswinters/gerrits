<?php


namespace App\Routes;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\View\View;
use Komma\KMS\Sites\SiteServiceInterface;

final class BreadcrumbComposer
{

    private SiteServiceInterface $siteService;

    public function __construct()
    {
        $this->siteService = app(SiteServiceInterface::class);
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $breadcrumbList = $this->makeBreadcrumbForUri($_SERVER['REQUEST_URI']);;
        $view->with('breadcrumbList', $breadcrumbList);
    }

    /**
     * Make Breadcrumb object out of the uri
     *
     * @param  string  $uri
     * @return object[]
     */
    public function makeBreadcrumbForUri(string $uri)
    {
        $breadcrumbList = [ $this->makeRootBread() ];

        // If root then we can already return
        // Or root with language for when multi language
        if($uri == "/" || config('app.multipleLanguages') && $uri == '/' . app()->getLocale() ) {
            return $breadcrumbList;
        }

        // Also handle the segments of the uri
        $this->handleSegments($breadcrumbList, $uri);
        return $breadcrumbList;
    }

    /**
     * Make root bread object
     *
     * @return object
     */
    private function makeRootBread()
    {
        $rootBread = (object)[
            'route' => request()->root(),
            'name'  => __('breadcrumb.rootName'),
        ];

        // If multi language we also add the current language to the root route.
        if(config('app.multipleLanguages') && App::getLanguage()->id !== $this->siteService->getCurrentSiteDefaultLanguage()) {
            $rootBread->route .= '/' . app()->getLocale();
        }

        return $rootBread;
    }

    /**
     * Handle the other segments of the requested path
     *
     * @param  array  $breadcrumbList
     * @param  string  $uri
     */
    private function handleSegments(array &$breadcrumbList, string $uri)
    {
        // Remove the root slash from the request path, and explode into segments
        $uri = substr($uri, 1);
        $segments = explode('/', $uri);

        // If multi language is enabled then the first segment can be skipped
        if(config('app.multipleLanguages')) array_shift($segments);

        // Loop through the segments
        foreach ($segments as $key => $segment) {

            // Check if the segment has a translation defined.
            $translationKey = 'breadcrumb.' . $segment;

            // If the key and translation are the same, we haven't defined a manual translation
            // Therefor we do the default conversion
            if(!trans()->has($translationKey)) {

                $translation = ucfirst($segment);
                $translation = str_replace('-', ' ', $translation);
            }
            else $translation = __($translationKey);

            $lastCrumbPath = end($breadcrumbList)->route;

            // Append the language segment when multi language is enabled and we are on the default language
            if(config('app.multipleLanguages') && App::getLanguage()->id === $this->siteService->getCurrentSiteDefaultLanguage() && $key == 0) {
                $lastCrumbPath .= '/' . app()->getLocale();
            }

            // Append to the breadcrumb list
            $breadcrumbList[] = (object)[
                'route' => $lastCrumbPath . '/' . $segment,
                'name' => $translation
            ];
        }
    }
}