<?php declare(strict_types=1);


namespace App\Site;


use App\Routes\RouteService;
use Illuminate\Database\Eloquent\Model;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;
use Komma\KMS\Sites\Kms\SiteController as KmsSiteController;


class SiteController extends KmsSiteController
{
    /** @var RouteService */
    private $routeService;

    public function __construct()
    {
        parent::__construct();
        $this->routeService = app(RouteService::class);
    }

    /**
     * This method is called when a item will be deleted
     *
     * @param Model $siteModel
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Model $siteModel)
    {
        /** @var Site $siteModel */
        $this->authorize('destroy', $siteModel);

        $siteModel->load(['pages', 'posts', 'products', 'product_groups', 'product_composites', 'rates']);


        $siteModel->pages->each(function(TreeModelInterface $model) use($siteModel) {
            if(!$model->exists) return;
            $this->routeService->destroyForModel($model);
            parent::destroy($model);
        });
        $siteModel->posts->each(function(Model $model) use($siteModel) {
            parent::destroy($model);
        });
        $siteModel->products->each(function(Model $model) use($siteModel) {
            parent::destroy($model);
        });
        $siteModel->product_groups->each(function(Model $model) use($siteModel) {
            parent::destroy($model);
        });
        $siteModel->product_composites->each(function(Model $model) use($siteModel) {
            parent::destroy($model);
        });
        $siteModel->rates->each(function(Model $model) use($siteModel) {
            parent::destroy($model);
        });

        return parent::destroy($siteModel);
    }
}