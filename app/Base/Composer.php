<?php


namespace App\Base;



use App\Site\Site;
use Komma\KMS\Sites\SiteServiceInterface;
use Illuminate\View\View;

abstract class Composer
{
    /** @var SiteServiceInterface */
    private $siteService;

    /** @var Site  */
    protected $site;

    public function __construct()
    {
        if(\App::runningInConsole()) return;
        $this->siteService = app(SiteServiceInterface::class);
        $this->site = $this->siteService->getCurrentSite();
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    abstract public function compose(View $view);
}