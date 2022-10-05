<?php


namespace App\Pages;

use App\Base\Service;
use App\Buttons\Models\Button;
use App\Pages\Models\Page;
use App\Servicepoints\Models\Servicepoint;

final class PageService extends Service
{

    /**
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
     */
    public function getPage(int $id)
    {
        // Find all pages
        if( ! $page = $this->site
            ->pages()
            ->where('active',1)
            ->where('id', $id)
            ->first()
        ) abort(404);

        return $page;
    }

    /**
     * Fetch translated page routes
     *
     * @return object | bool
     */
    public function getAllTranslatedPageRoutes()
    {
        // Find all pages
        if( ! $pages = $this->site
            ->pages()
            ->where('active',1)
            ->where('lft', '!=', 1)
            ->orderBy('lft','asc')
            ->with('translation')
            ->with('translation.route')
            ->get()
        ) return false;

        $routes = [];
        // Loop through pages
        foreach($pages as $key => $page)
        {
            if(isset($page->translation) && isset($page->translation->route)){
                $routes[$page->code_name] = (object)[
                    'name' => $page->translation->name,
                    'route' => $page->translation->route->alias,
                    'node' => $page
                ];
            }
        }

        return (object)$routes;
    }

    public function setSharableVariables($servicePointId = null, $servicePointButtonId = null, $servicePointHeading = null) {

        // if no servicePoint heading title is set on the page use the value from global config
        if(empty($servicePointHeading)) $servicePointHeading = config('site.global_servicePoint_heading');

        // if no servicePointId is set on the page use tHe value from global config
        if(!isset($servicePointId) || $servicePointId == '0') $servicePointId = config('site.global_servicePoint_id');
        $servicePoint = Servicepoint::where('id', $servicePointId)->with('translations', 'documents')->first();

        // if no servicePointButtonId is set on the page use te value from global config
        if(!isset($servicePointButtonId) || $servicePointButtonId == '0') $servicePointButtonId = config('site.global_servicePoint_button_id');
        $servicePointButton = Button::where('id', $servicePointButtonId)->with('translations')->first();

        view()->share([
            'servicePointHeading' => $servicePointHeading,
            'servicePoint' => $servicePoint,
            'servicePointButton' => $servicePointButton,
        ]);
    }

    /**
     * Generate a sub navigation (children) through the page
     *
     * @param  Page  $page
     * @param $links
     * @return array
     */
    public function getSubNav(Page $page, $links)
    {
        $subNav = [];

        $children = $page->findChildren();
        foreach ($children as $child) $subNav[] = $links->{$child->code_name};

        return $subNav;
    }


    /**
     * Make Language switch for page
     * This will make a object with two arrays, one with and one without fallback.
     * Fallback for language switch. Without fallback for meta alternatives.
     *
     * @param $page
     * @param  null  $home
     * @return object
     */
    public function makeLanguageSwitchForPage($page, $home = null)
    {

        $languageMenu = (object)[
            'withoutFallback' => [],
            'withFallback' => [],
        ];

        $pageTranslations = $page->node->translations->keyBy('language_id');

        if(!empty($home)) $homeTranslations = $home->node->translations->keyBy('language_id');

        foreach ($this->site->languages as $language)
        {
            $pageTranslation = $pageTranslations->get($language->id);

            if(isset($pageTranslation) && !empty($pageTranslation->slug))
            {

                if(isset($pageTranslation->route)){
                    if($pageTranslation->route->alias != '/') $route = $pageTranslation->route->alias;
                    else $route = $pageTranslation->route->alias;

                    $languageMenu->withoutFallback[$language->iso_2] = $route;
                    $languageMenu->withFallback[$language->iso_2] = $route;
                }
                continue;
            }

            // If page hasn't a translation grab the home route (should always exists
            if(!empty($home) && $homeTranslation = $homeTranslations->get($language->id))
            {
                if($homeTranslation->route->alias != '/') $route = $homeTranslation->route->alias;
                else $route = $homeTranslation->route->alias;

                $languageMenu->withFallback[$language->iso_2] = $route;

                continue;
            }
        }


        return $languageMenu;
    }

    /**
     * Extend the language menu with the given resource
     *
     * @param $languageMenu
     * @param $resource
     */
    public function extendLanguageMenuWithResource(&$languageMenu, $resource)
    {
        if(sizeof($languageMenu->withFallback) == 0) return;

        $resourceTranslations = $resource->translations->keyBy('language_id');

        foreach ($this->site->languages as $language)
        {

            $resourceTranslation = $resourceTranslations->get($language->id);

            if(isset($resourceTranslation) && !empty($resourceTranslation->slug))
            {

                // Append the found resource translation to given language menu item if active
                if(isset($languageMenu->withFallback[$language->iso_2])){
                    $languageMenu->withFallback[$language->iso_2] .= '/' . $resourceTranslation->slug;
                }

                if(isset($languageMenu->withoutFallback[$language->iso_2])){
                    $languageMenu->withoutFallback[$language->iso_2] .= '/' . $resourceTranslation->slug;
                }

            }
            else{
                unset($languageMenu->withoutFallback[$language->iso_2]);
            }
        }
    }

}