<?php
namespace Database\Seeders;

use App\Routes\Models\Route;
use Illuminate\Support\Facades\Log;
use Komma\KMS\Sites\Models\Site;
use Illuminate\Database\Seeder;

class CorrectRoutesForWildcardModelsSeeder extends Seeder
{
    /**
     * Run the seed
     */
    public function run()
    {
        $routes = Route::all();

        $routes->each(function(Route $route) {
            /** @var \App\Pages\Models\PageTranslation $pageTranslation */
            $pageTranslation = $route->routable()->first();
            if(!$pageTranslation) return; //Break

            /** @var \App\Pages\Models\Page $page */
            $page = $pageTranslation->translatable()->first();

            $type = ucfirst($page->code_name);
            if(class_exists('App\Http\Wildcards\\'.$type.'Wildcard')) {
                $route->route = $page->code_name;
                $route->save();
            };
        });
    }
}