<?php


namespace App\Composers;


use App\WebsiteConfig\Model\WebsiteConfig;
use Illuminate\View\View;

class LogoComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $logoOnDark = WebsiteConfig::where('code_name', '=', 'logo_on_dark')->first();
        $logoOnLight = WebsiteConfig::where('code_name', '=', 'logo_on_light')->first();


        if(!empty($logoOnDark) && $logoOnDark->documents->count() > 0) {
            $logoOnDark = $logoOnDark->documents->first()->file_url;
        } else {
            $logoOnDark = '/img/logo-on-dark.svg';
        }

        if(!empty($logoOnLight) && $logoOnLight->documents->count() > 0) {
            $logoOnLight = $logoOnLight->documents->first()->file_url;
        } else {
            $logoOnLight = '/img/logo.svg';
        }

        $view->with(['logoOnDark' => $logoOnDark, 'logoOnLight' => $logoOnLight]);
    }
}