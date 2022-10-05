<?php


namespace App\Sitemap;

use App\Base\Service;
use App\Pages\Models\Page;
//use App\Posts\Models\Post;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;

final class SitemapService extends Service
{
    /**
     * Generate a sitemap xml
     *
     * @return Sitemap
     */
    public function makeSitemap()
    {

        $sitemap = app(Sitemap::class);
        $this->addPagesToSitemap($sitemap);
//        $this->addWildcardModelToSitemap($sitemap, 'posts', Post::class);
//        $this->addWildcardModelToSitemap($sitemap, 'updates', Update::class);

        return $sitemap;
    }

    /**
     * Append a prepared xml array to the sitemap
     *
     * @param array $xmlArray
     * @param Sitemap $sitemap
     */
    private function addXmlArrayToSitemap(Array $xmlArray, Sitemap &$sitemap)
    {
        foreach ($xmlArray as $xmlObject){
            if(isset($xmlObject->translation)){
                $sitemap->add($xmlObject->loc, Carbon::now(), 0.5,'monthly',[],[],$xmlObject->translation);
            }
            else{
                $sitemap->add($xmlObject->loc, Carbon::now(), 0.5,'monthly');
            }
        }
    }


    /**
     * Add pages to the sitemap
     *
     * @param Sitemap $sitemap
     */
    private function addPagesToSitemap(Sitemap &$sitemap)
    {

        $pages = $this->site
            ->pages()
            ->where('lft', '!=', 1)
            ->where('active', 1)
            ->with('translations')
            ->with('translations.route')
            ->get();

        // Prepare xml array for appending to the sitemap
        $xmlPages = [];
        foreach ($pages as $page) {


            // Generate for multiple languages
            if(config('app.multipleLanguages')){
                $pageTranslations = [];

                // First we need to create an array of the available translations of this page
                foreach ($page->translations as $translation) {

                    if(!isset($translation->route->alias)) continue;

                    $alias = $translation->route->alias;
                    if($alias === '/') $alias = '';

                    $pageTranslations[] = [
                        'url'      => \URL::to($alias),
                        'language' => $translation->getLanguageIso()
                    ];
                }

                // Then append for each individual page the translation alias with all available translations of this page to the xml array
                foreach ($pageTranslations as $translation) {
                    $xmlPages[] = (object)[
                        'loc'         => \URL::to($translation['url']),
                        'translation' => $pageTranslations
                    ];
                }
            }
            // Generate for single language
            else{

                $alias = $page->translations->first()->route->alias;
                if($alias === '/') $alias = '';

                $xmlPages[] = (object)[
                    'loc' => \URL::to($alias),
                ];
            }
        }

        $this->addXmlArrayToSitemap($xmlPages, $sitemap);
    }

    private function addWildcardModelToSitemap(Sitemap &$sitemap, string $code_name, string $modelClass)
    {
        // Get the post index
        $overviewPage = Page::where('code_name', $code_name)
            ->with('translations')
            ->with('translations.route')
            ->where('active', 1)
            ->first();

        // If the post index isn't found or inactive skip the posts from adding to the sitemap
        if(!isset($overviewPage)) return null;

        // Load the posts
        $models = $modelClass::with('translations')
            ->has('translations')
            ->where('active', 1)
            ->get();

        // Prepare xml array for appending to the sitemap
        $xmlModels = [];
        foreach ($models as $model) {


            // Generate for multiple languages
            if(config('app.multipleLanguages')){
                $modelTranslations = [];

                // First we need to create an array of the available translations of this post
                foreach ($model->translations as $translation) {

                    $overviewPageTranslation = $overviewPage->translations->where('language_id', '=', $translation->language_id)->first();

                    if(!isset($translation->slug) || !isset($overviewPageTranslation) || $translation->slug === '') continue;

                    $alias = $overviewPageTranslation->route->alias . '/' . $translation->slug;

                    $modelTranslations[] = [
                        'url'      => \URL::to($alias),
                        'language' => $translation->getLanguageIso()
                    ];
                }

                // Then append for each individual post the translation alias with all available translations of this page to the xml array
                foreach ($modelTranslations as $translation) {
                    $xmlModels[] = (object)[
                        'loc'         => \URL::to($translation['url']),
                        'translation' => $modelTranslations
                    ];
                }
            }
            // Generate for single language
            else{

                $alias = $overviewPage->translations->first()->route->alias.'/'.$model->translations->first()->slug;
                if($alias === '/') $alias = '';

                $xmlModels[] = (object)[
                    'loc' => \URL::to($alias),
                ];
            }
        }

        $this->addXmlArrayToSitemap($xmlModels, $sitemap);

    }
}