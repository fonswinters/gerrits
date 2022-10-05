<?php

namespace App\Routes;


use App\Routes\Models\RedirectRoute;
use App\Routes\Models\Route;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Komma\KMS\Globalization\Languages\Models\Language;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\AbstractTranslationModel;
use Komma\KMS\Core\HouseKeeping\CanDoHousekeepingInterface;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;
use Komma\KMS\Helpers\KommaHelpers;
use Komma\KMS\Sites\Models\Site;
use Komma\KMS\Sites\SiteServiceInterface;

/**
 * Class RouteService
 *
 * Can create routes for AbstractTranslationModels that are part of a given AbstractTranslatable model.
 *
 * @package App\Routes
 */
class RouteService extends AbstractRouteService implements CanDoHousekeepingInterface
{
    /** @var SiteServiceInterface $siteService */
    private $siteService;

    public function __construct()
    {
        $this->siteService = app(SiteServiceInterface::class);

        static::setRouteClassName(Route::class);
        static::setRedirectRouteClassName(RedirectRoute::class);
    }

    /**
     * Creates or updates routes for the specified AbstractTranslatableModel AbstractTranslationModels if needed.
     * Important to know is that you need to run this method after the translation has been saved.
     *
     * @param Model $model
     * @param int $redirectCode one of the HTTPStatusCode_308 constants from RedirectRouteModelInterface. Defaults to RedirectRouteModelInterface::HTTPStatusCode_308
     * @return Model $model
     */
    public function createOrUpdateRoutesForModelsTranslationsIfChanged(Model $model, int $redirectCode = RedirectRouteModelInterface::HTTPStatusCode_308): Model
    {
        if(!is_a($model, AbstractTranslatableModel::class)) return $model;

        /** @var AbstractTranslatableModel $model */
        $translations = $model->translations()->get();

        //We need to make sure that each translation has a language and routes method. If not trow an exception
        $this->verifyThatEachHasTranslationIsAnAbstractTranslationModel($translations);

        //Process routes for the models translations
        $translations->each(function (AbstractTranslationModel $translation) use ($redirectCode, $model, &$existingRouteFails) {
            /** @var AbstractTranslationModel|HasRoutesInterface $translation */
            /** @var Language $language */
            if ( ! is_a($translation, HasRoutesInterface::class)) return $model;

            // Don't make a route for an empty translation, because they shouldn't even exist
            if ($translation->name === '') return $model;

            $language = $translation->language()->get()->first();
            $newRouteAliasStringForCurrentTranslation = $this->createRouteAliasString($language, $model, $translation);

            //Get route and redirect route
            /** @var RouteModelInterface $currentTranslationRoute */
            $currentTranslationRoute = $translation->route()->first();

            $this->createOrUpdateRouteForTranslationIfChanged($translation, $newRouteAliasStringForCurrentTranslation, $currentTranslationRoute, $model->site);
        });

        //Process routes for the children of the model if it is a tree model interface
        if(is_a($model, TreeModelInterface::class)) {
            /** @var $model AbstractTranslatableModel|TreeModelInterface $children */
            $children = collect($model->findChildren());
            $children->each(function(TreeModelInterface $child) {
                $this->createOrUpdateRoutesForModelsTranslationsIfChanged($child);
            });
        }

        return $model;
    }

    /**
     * Creates or updates a route for the AbstractTranslationModel
     *
     * @param AbstractTranslationModel $translation
     * @param string $newRouteAliasStringTranslation
     * @param RouteModelInterface $currentTranslationRoute
     * @param Site|null $site
     * @throws \ReflectionException
     */
    private function createOrUpdateRouteForTranslationIfChanged(AbstractTranslationModel $translation, string $newRouteAliasStringTranslation, RouteModelInterface $currentTranslationRoute = null, Site $site = null)
    {
        /** @var AbstractTranslatableModel $model */
        $model = $translation->translatable()->first();

        $routeWithNewAliasQuery = $this->getRouteClassName()::where('alias', '=', $newRouteAliasStringTranslation);
        if($site) $routeWithNewAliasQuery->where('site_id', $site->id);

        $routeWithNewAlias = $routeWithNewAliasQuery->first();

        $translationRedirectRouteWithNewAliasQuery = $translation->redirectRoutes()->where('alias', '=', $newRouteAliasStringTranslation);
        if($site) $translationRedirectRouteWithNewAliasQuery->where('site_id', $site->id);

        $translationRedirectRouteWithNewAlias = $translationRedirectRouteWithNewAliasQuery->first();

        //skip this iteration. Route did not change
        if ($currentTranslationRoute && $this->removeNumberSuffixIfPresent($currentTranslationRoute->alias) == $this->removeNumberSuffixIfPresent($newRouteAliasStringTranslation)) return;

        // Handle different the of situations between both route types.
        // When the new route is already in the redirect table we need to swap the detail of the current regular route
        if ($currentTranslationRoute && $translationRedirectRouteWithNewAlias) {
//            dd('0');

            $translationRedirectRouteWithNewAlias->alias = $currentTranslationRoute->alias;
            $currentTranslationRoute->alias = $newRouteAliasStringTranslation;

            $translationRedirectRouteWithNewAlias->save();
            $currentTranslationRoute->save();
        } // When the new route does exist as a regular route we create a new redirect route containing the regular route's details. We then give the already existing regular route the new details
        elseif ($currentTranslationRoute && ! $translationRedirectRouteWithNewAlias) {
//            dd('1');

            $redirectRoute = $this->createRedirectRouteFromRouteAndRedirectCode($currentTranslationRoute,
                $this->getRedirectRouteClassName()::Http11PermanentCachedByDefault);
            $redirectRoute->site_id = $model->site_id;

            $currentTranslationRoute->alias = $newRouteAliasStringTranslation;

            //Check if there already is an redirect route with the same alias
            $possibleExistingRouteRedirectRouteQuery = $this->getRedirectRouteClassName()::where('alias', '=', $currentTranslationRoute->alias);
            if($site) $possibleExistingRouteRedirectRouteQuery->where('site_id', $model->site_id);

            $possibleExistingRouteRedirectRoute = $possibleExistingRouteRedirectRouteQuery->first();

            if ($possibleExistingRouteRedirectRoute) {
                \Log::warning('Deleted redirect route with alias "' . $newRouteAliasStringTranslation . '". Because a new one with the same alias needed to be created.');
                $possibleExistingRouteRedirectRoute->delete();
            }

            $redirectRoute->save();
            $currentTranslationRoute->save();
        }//Else if the new route exists as redirect route but not as regular route, we only log this result. The redirect route works but could be improved by storing him as regular route
        elseif ( ! $currentTranslationRoute && $translationRedirectRouteWithNewAlias) {
//                            dd('2');
            $route = $this->createRoute($newRouteAliasStringTranslation, $model, $translation);

            $translationRedirectRouteWithNewAlias->delete();
            $route->save();
            $translationRedirectRouteWithNewAlias = null;
        }//Else if when the route does not exist on both route types we create it as regular route
        elseif ( ! $currentTranslationRoute && ! $routeWithNewAlias && ! $translationRedirectRouteWithNewAlias) {
//                            dd('3');
            $route = $this->createRoute($newRouteAliasStringTranslation, $model, $translation);
            $route->save();
        }
    }

    /**
     * Removes the number suffix from a string.
     * @param string $string The subject
     * @param string $delimiter Character that separates the number from the rest.
     * @return string
     */
    private function removeNumberSuffixIfPresent(string $string, string $delimiter = '-')
    {
        $shrapnel = explode($delimiter, $string);
        $potentialNumber = last($shrapnel);
        if ( ! is_numeric($potentialNumber)) {
            return $string;
        }
        array_pop($shrapnel);

        return implode($delimiter, $shrapnel);
    }

    /**
     * Check if the given route alias only is defined once in either RouteModelInterface and RedirectRouteModelInterface instances.
     * If not throw a RunTimeException
     *
     * @param string $alias
     * @return void
     * @throws \RuntimeException
     */
    private static function checkRouteTableIntegrityForAlias(string $alias = '')
    {
        $redirectRouteClassName = self::getRedirectRouteClassName();
        $routeClassName = self::getRouteClassName();

        //If no alias was given, we check all aliases.
        if($alias == '') {
            //Go and check all route aliases
            $routeClassName::all(['alias'])->each(function($route) {
                self::checkRouteTableIntegrityForAlias($route->alias);
            });

            $redirectRouteClassName::all(['alias'])->each(function($route) {
                self::checkRouteTableIntegrityForAlias($route->alias);
            });
            return;
        }

        //Return all routes with the given alias and group them by site_id
        $routesWithAlias = $routeClassName::get(['id', 'alias', 'site_id'])->where('alias', '=', $alias)->groupBy('site_id');
        $redirectRoutes = $redirectRouteClassName::get(['id', 'alias', 'site_id'])->where('alias', '=', $alias)->groupBy('site_id');

        $routesWithAlias->each(function(DatabaseCollection $routesCollection, $site_id) use ($routeClassName, $alias) {
            if($routesCollection->count() > 1) {
                throw new \RuntimeException('Check your "' . (new $routeClassName)->getTable() . '" table. It contains multiple routes with alias "' . $alias . '" and the same site_id of "'.$site_id.'". which is not allowed.');
            }
        });


        $redirectRoutes->each(function(DatabaseCollection $routesCollection, $site_id) use ($redirectRouteClassName, $alias) {
            if($routesCollection->count() > 1) {
                throw new \RuntimeException('Check your "' . (new $redirectRouteClassName)->getTable() . '" table. It contains multiple routes with alias "' . $alias . '" and the same site_id of "'.$site_id.'". which is not allowed.');
            }
        });

        $routesWithAlias->each(function(DatabaseCollection $routesCollection, $site_id) use ($redirectRoutes, $alias, $redirectRouteClassName, $routeClassName) {
            $redirectRoutesCollectionForCurrentRouteSiteId = $redirectRoutes->get($site_id);
            if($redirectRoutesCollectionForCurrentRouteSiteId && $redirectRoutesCollectionForCurrentRouteSiteId->count() > 0) {
                throw new \RuntimeException('Check your "' . (new $redirectRouteClassName)->getTable() . '" and "' . (new $routeClassName)->getTable() . '" tables. They both contain a route with alias "' . $alias . '" and site_id "'.$site_id.'" which is not allowed. Only one in both tables combined is allowed.');
            }
        });


//        if ($redirectRoutesWithNewAliasCount + $routesWithNewAliasCount > 1) {
//            throw new \RuntimeException('Check your "' . (new $redirectRouteClassName)->getTable() . '" and "' . (new $routeClassName)->getTable() . '" tables. They both contain a "' . $alias . '" which is not allowed. Only one in both tables combined is allowed');
//        }
    }

    /**
     * Creates a route model (does not save it to the database)
     *
     * If you give it the AbstractTranslatableModel model it will update the route attribute to the appropiate value.
     * If you give it the AbstractTranslationModel model it will update the routable_id and routable_type attributes so
     * that the AbstractTranslationModel instance is associated with it
     *
     * @param string $alias The alias for the route.
     * @param AbstractTranslatableModel|null $model
     * @param AbstractTranslationModel|null $translationModel
     * @return RouteModelInterface
     */
    private function createRoute(string $alias, AbstractTranslatableModel $model, AbstractTranslationModel $translationModel): RouteModelInterface
    {
        $routeClass = $this->getRouteClassName();
        /** @var RouteModelInterface|Route $routeModel */
        $routeModel = new $routeClass;
        $routeModel->route = ($model) ? $this->generateRealRouteForModel($model) : '';
        $routeModel->alias = $alias;
        $routeModel->language_id = $translationModel->language()->get(['id'])->first()->id;
        $routeModel->site()->associate($model->site_id);
        $routeModel->routable()->associate($translationModel);

        return $routeModel;
    }

    /**
     * Generates a real route for a model that should be compatible with the routes listed
     * with php artisan route:list.
     * Example: when you pass a page model, it will return: pages/4 if the page id is 4.
     *
     * @param Model $model
     * @return string
     */
    protected function generateRealRouteForModel(Model $model)
    {
        $shortName = KommaHelpers::getShortNameFromClass($model);

        $shortName = strtolower($shortName);
        $pluralShortName = Str::plural($shortName);

        return $pluralShortName . '/' . $model->id;
    }

    /**
     * Creates an instance of a RedirectRouteModelInterface based on an instance of RouteModelInterface.
     * And then returns the RedirectRouteModelInterface
     *
     * @param RouteModelInterface $route
     * @param int $redirectCode
     * @return RedirectRouteModelInterface;
     * @throws \ReflectionException
     */
    private function createRedirectRouteFromRouteAndRedirectCode(RouteModelInterface $route, int $redirectCode): RedirectRouteModelInterface
    {
        $attributes = $route->toArray();

        $redirectRouteClassName = $this->getRedirectRouteClassName();
        /** @var RedirectRouteModelInterface|RedirectRoute $redirectRoute */
        $redirectRoute = new $redirectRouteClassName();

        $redirectRoute->fill($attributes);

        if ( ! $this->getRedirectRouteClassName()::isValidRedirectCode($redirectCode)) {
            throw new \InvalidArgumentException("The redirect code '" . $redirectCode . "' was not a valid one. It must be one of the constants defined in the RedirectRouteModelInterface.");
        }
        $redirectRoute->redirect_code = $redirectCode;

        return $redirectRoute;
    }

    /**
     * Returns a route alias string for an instance that extends the AbstractTranslationModel class
     *
     * @param Language $language
     * @param AbstractTranslatableModel $model
     * @param AbstractTranslationModel $translation
     * @return string
     * @see AbstractTranslationModel
     */
    private function createRouteAliasString(Language $language, AbstractTranslatableModel $model, AbstractTranslationModel $translation): string
    {
        $availableLanguages = $this->siteService->getSiteLanguages();
        $defaultLanguageId = $this->siteService->getCurrentSite()->default_language_id;

        if (count($availableLanguages) == 0) {
            throw new \RuntimeException("Site has no language");
        }

        // Check if model has EloquentNodeInterface
        if (is_a($model, TreeModelInterface::class)) {

            // Handles the route creating for eloquent nodes
            $potentialRoute = $this->createPotentialRouteForEloquentNode($language, $model, $translation, $availableLanguages, $defaultLanguageId);
        } else {
            if (count($availableLanguages) == 1) {
                $potentialRoute = '/' . Str::slug($translation->name);
            }else {
                if ($translation->slug == 'home') {
                    $potentialRoute = '/' . $language->iso_2;
                }else {
                    $potentialRoute = '/' . $language->iso_2 . '/' . $translation->slug;
                }
            }
        }

        $sitesIds = $this->siteService->getSiteIdsForModel($model, false);

        $alreadyExistsQuery = $this->getRouteClassName()::where('alias', '=', $potentialRoute);
        if($sitesIds) $alreadyExistsQuery->whereIn('site_id', $sitesIds);

        $alreadyExists = $alreadyExistsQuery->first();

        if ($alreadyExists) {
            $suffixedRoutes = $this->getRouteClassName()::where('alias', 'like', $potentialRoute . '-%')
                ->where('routable_id', '!=', $translation->id)
                ->where('routable_type', '==', get_class($model))
                ->where('site_id', $model->site_id)
                ->get(['alias']);
            if ($suffixedRoutes->count() > 0) {
                $highestNumber = null;
                $suffixedRoutes->each(function ($suffixedRoute) use (&$highestNumber) {
                    $kaboomedAlias = explode('-', $suffixedRoute->alias);
                    $number = end($kaboomedAlias);

                    if (is_numeric($number)) {
                        $currentNumber = intval($number);
                        if ($highestNumber < $currentNumber) {
                            $highestNumber = $currentNumber;
                        }
                    }
                });

                $potentialRoute .= ($highestNumber) ? '-' . ($highestNumber + 1) : '-1';
            } else {
                $potentialRoute .= '-1';
            }
        }

        return $potentialRoute;
    }

    /**
     * Returns a possible route alias string for an instance that extends the AbstractTranslationModel and has an EloquentNodeInterface
     *
     * @param Language $language
     * @param AbstractTranslatableModel $model
     * @param AbstractTranslationModel $translation
     * @param array $availableLanguages
     * @param integer $defaultLanguageId
     * @return string
     */
    private function createPotentialRouteForEloquentNode(Language $language, AbstractTranslatableModel $model, AbstractTranslationModel $translation, $availableLanguages, $defaultLanguageId): string
    {
        if(is_a($model,TreeModelInterface::class)) {
            /** @var TreeModelInterface $model */
            $parent = $model->getParent();
        } else {
            $parent = null;
        }

        // Check if model is direct parent of root
        if ($parent->lft == '1') {

            // If this language is the default language and the translation slug is home
            // Then we name this route the root
            if($language->id === $defaultLanguageId && $translation->slug == 'home'){
                return '/';
            }

            //Then do as normal
            // If doesn't have multiple language then just slugify the name
            if (count($availableLanguages) == 1) {
                return '/' . Str::slug($translation->name);
            }else {
                // Else we add a prefix of the iso_2
                // With an exception on home because that is just the iso_2 alone
                if ($translation->slug == 'home') {
                    return $potentialRoute = '/' . $language->iso_2;
                }else {
                    return '/' . $language->iso_2 . '/' . $translation->slug;
                }
            }
        }else { // Then model is a sub model so generate route by alias
            // Load parent translation
            $parentTranslation = $parent->translations->where('language_id', '=', $language->id)->first();

            $parentTranslationRoute = $parentTranslation->route;
            if(!$parentTranslationRoute) throw new \RuntimeException('Parent translation with id '.$parentTranslation->id.' did not have a route.');

            // Sub model route is parent route with slugify name
            return $parentTranslationRoute->alias . '/' . Str::slug($translation->name);
        }
    }

    /**
     * Check that all translations in the collection are AbstractTranslationModel instances.
     * This means that they have a routes and language method at least.
     *
     * @see HasLanguageAndRoutesInterface
     * @see HasTranslationsInterface
     * @param DatabaseCollection $translations A Collection with AbstractTranslationModel implementations
     * @return void
     */
    private function verifyThatEachHasTranslationIsAnAbstractTranslationModel(DatabaseCollection $translations)
    {
        $translations->each(function ($translation) {
            if ( ! is_a($translation, AbstractTranslationModel::class)) {
                throw new \RuntimeException("One of the translations wasn't an AbstractTranslationModel implementation while it must be one.");
            }
        });
    }

    /**
     * Returns the redirect route (if any) with the specified alias or
     * if that not exists it returns the regular route or if that does not exists null
     *
     * @param string $alias
     * @param Site $site
     * @param string $routeInterfaceNameToReturn if null it will try to find a RouteRedirectModelInterface or a RouteModelInterface if the RouteRedirectModelInterface does not exist.
     * You can search for a specific type by passing either RouteRedirectModelInterface::class or RouteModelInterface::class
     * @param bool $mustBeActive The route must link to an active routeable (true) or we don't care (false)
     * @return RedirectRouteModelInterface|RouteModelInterface|null
     */
    public function getRouteByAlias(string $alias, Site $site, bool $mustBeActive = true, string $routeInterfaceNameToReturn = null): ?Model
    {
        $routes = null;

        if($routeInterfaceNameToReturn == null) {
            /** @var RouteModelInterface $route */
            $redirectRoutes = $this->getRedirectRouteClassName()::where('alias', $alias)
                ->orderBy('updated_at', 'desc')
                ->where('site_id', $site->id)
                ->get();
            if ($redirectRoutes->count() == 0) {
                $regularRoutes = $this->getRouteClassName()::where('alias', $alias)
                    ->orderBy('updated_at', 'desc')
                    ->where('site_id', $site->id)
                    ->get();
                if ($regularRoutes->count() > 0) $routes = $regularRoutes;
            } else {
                $routes = $redirectRoutes;
            }
        }
        elseif($routeInterfaceNameToReturn == RedirectRouteModelInterface::class)
        {
            $redirectRoutes = $this->getRedirectRouteClassName()::where('alias', $alias)
                ->orderBy('updated_at', 'desc')
                ->where('site_id', $site->id)
                ->get();
            if ($redirectRoutes->count() == 0) return null;
            $routes = $redirectRoutes;
        }
        elseif($routeInterfaceNameToReturn == RouteModelInterface::class)
        {
            $regularRoutes = $this->getRedirectRouteClassName()
                ->orderBy('updated_at', 'desc')
                ->where('site_id', $site->id)
                ->get();
            if ($regularRoutes->count() == 0) return null;
            $routes = $regularRoutes;
        } else {
            throw new \InvalidArgumentException("The routeType argument must be either null, RouteRedirectModelInterface::class or RouteModelInterface::class");
        }

        if($routes)
        {
            if($mustBeActive == false) return $routes->first();

            //check if one of the routeables is active and return the first
            foreach($routes as $route){

                /** @var RouteModelInterface $route */
                $routeable = $route->routable()->first()->translatable;
                if(!is_a($routeable, AbstractTranslatableModel::class)) throw new \RuntimeException('The model returned with the "routable" method from a RedirectRouteModelInterface implementation "'.get_class($route).'" did not return the expected model that extends the AbstractTranslatableModel.');

                /** @var AbstractTranslatableModel $routeable */
                if(isset($routeable->active) && $routeable->active && $routeable->id != false){
                    return $route;
                } //Skip this iteration
            }
        }

        return null;
    }

    /**
     * Does some housekeeping by removing old routes.
     * Should be triggered by cron jobs via a houseKeeperService
     */
    public static function doHouseKeeping()
    {
        static::setRouteClassName(Route::class);
        static::setRedirectRouteClassName(RedirectRoute::class);

        $result = '';

        try {
            self::checkRouteTableIntegrityForAlias();
        } catch (\RuntimeException $e) {
            $result .= $e->getMessage().PHP_EOL;
        }
        return $result;
    }

    /**
     * Destroys a Eloquent models routes
     *
     * @param Model|\Illuminate\Support\Collection $model
     * @return AbstractTranslatableModel|HasRoutesInterface|Model|\Illuminate\Support\Collection
     */
    public function destroyForModel(Model $model)
    {
        if(is_a($model, TreeModelInterface::class)) {
            /** @var TreeModelInterface $model */
            $children = $model->getChildren();
            if(!$children) $children = $model->findChildren();

            foreach($children as $child) {
                $this->destroyForModel($child);
            }
        }


        if(is_a($model, AbstractTranslatableModel::class)) {
            /** @var AbstractTranslatableModel $model */
            $model->translations()->get()->each(function (AbstractTranslationModel $abstractTranslationModel) use(&$ids) {
                if (is_a($abstractTranslationModel, HasRoutesInterface::class)) {
                    $abstractTranslationModel->redirectRoutes()->delete();
                    $abstractTranslationModel->route()->delete();
                }
            });
        } elseif(is_a($model, HasRoutesInterface::class)) {
            /** @var HasRoutesInterface $model */
            $model->redirectRoutes()->delete();
            $model->route()->delete();
        }

        return $model;
    }
}
