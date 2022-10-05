<?php declare(strict_types=1);


namespace App\Pages\Kms;


use App\Pages\Models\Page;
use App\Pages\Models\PageTranslation;
use App\Routes\RedirectRouteModelInterface;
use App\Routes\RouteService;
use Illuminate\Database\Eloquent\Model;

class PageRouteService extends RouteService
{
    /**
     * Creates or updates routes for the specified Page's translations if needed.
     * Important to know is that you need to run this method after the translation has been saved.
     *
     * @param Model $model
     * @param int $redirectCode one of the HTTPStatusCode_308 constants from RedirectRouteModelInterface. Defaults to RedirectRouteModelInterface::HTTPStatusCode_308
     * @return Model $model
     */
    public function createOrUpdateRoutesForModelsTranslationsIfChanged(Model $model, int $redirectCode = RedirectRouteModelInterface::HTTPStatusCode_308): Model {

        /** @var Page $model */
        $model = parent::createOrUpdateRoutesForModelsTranslationsIfChanged($model, $redirectCode);

        $model->translations->each(function (PageTranslation $pageTranslation) use ($model) {

            if(!$pageTranslation->route) return;

            $route = $pageTranslation->route;

            //Turn it into a wildcard route if needed
            if ($model->has_wildcard) $route->route = $model->code_name;
            else $route->route = $this->generateRealRouteForModel($model);

            $route->save();
        });

        return $model;
    }
}