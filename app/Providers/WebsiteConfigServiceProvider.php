<?php

namespace App\Providers;

use App\WebsiteConfig\WebsiteConfigModelService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class WebsiteConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(app()->runningInConsole()) return;
        $config = WebsiteConfigModelService::getFromCache();
        foreach ($config as $setting) Config::set('site.'.$setting->code_name, $setting->value);
    }
}
