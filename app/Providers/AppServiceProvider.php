<?php

namespace App\Providers;

use App\Site\Site;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Komma\KMS\Sites\Models\SiteInterface;
//use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Needed for old MyQL versions
        Schema::defaultStringLength(191);

        if($this->app->environment('local'))
        {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }

        // You can use this to check if the modifier array isset and automatic call the atomic modifiers helper
        Blade::directive('modifiers', function ($expression) {

            // Strip single or dubble quotes
            $baseClass = str_replace('"', "", $expression);
            $baseClass = str_replace("'", "", $baseClass);

            // We echo the modifiers through the Modifiers helper
            // And we burn the defined modifiers

            return '<?php if(isset($modifiers)){ App\Helpers\KommaHelpers::modifiers("'.$baseClass.'", $modifiers); } $modifiers = []; ?>';
        });

        // Laravel 7 includes first-party support for Blade "tag components".
        // If you wish to disable Blade's built-in tag component functionality, you may call the withoutComponentTags method from the boot method of your AppServiceProvider.
        // Blade::withoutComponentTags();

        // The paginator now uses the Tailwind CSS framework for its default styling.
        // In order to keep using Bootstrap, you should add the following method call to the boot method of your application's AppServiceProvider.
        Paginator::useBootstrap();

        $this->app->bind(SiteInterface::class, Site::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (class_exists(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class)) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

}
