<?php


namespace App\Base;


//use App\Site\Site;
use App\Site\Site;
use Komma\KMS\Sites\SiteServiceInterface;

abstract class Service
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
}