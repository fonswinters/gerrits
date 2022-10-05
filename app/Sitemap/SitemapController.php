<?php

namespace App\Sitemap;


use App\Base\Controller;
use Illuminate\Support\Facades\Response;

final class SitemapController extends Controller
{


    /**
     * We only need the structure of the tree to get the pages
     * The content of the pages we can get through the links
     *
     * @param null $language
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($language = null)
    {
        // If $language isset and it doesn't match with the app getLocale redefine the application language by iso of $language
        // it probably means that the route is changed by manually changing the route
        if(isset($language) && $language !== \App::getLocale()){
            \App::changeLanguageByIso2($language);
            return \App::reload(); // Because the application has many language dependencies it better reload the whole application
        }

        $otherLanguages = [];
        foreach ($this->site->languages as $siteLanguage){
            $otherLanguages[$siteLanguage->id] = (object)[
                'iso' => $siteLanguage->iso_2,
                'route' => $siteLanguage->iso_2.'/sitemap'
            ];
        }

        return view('templates.sitemap',[
            'page' => $this->site->pages()->where('lft', 1)->first(),
            'links' => $this->links,
            'otherLanguages' => $otherLanguages
        ]);
    }

    /**
     * Generate the xml sitemap
     *
     * @return mixed
     */
    public function xml()
    {
        $sitemapService = new SitemapService();
        $sitemap = $sitemapService->makeSitemap();
        return $sitemap->render();
    }
}