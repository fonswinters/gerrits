<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Site\Site;

class RemoveGermanLanguageFromSiteLanguages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $site = Site::where('slug', '=', 'default')->first();

        if($site) {
            // Here we remove the German language (id:50) from the site_languages table
            // This is currently needed because it is automatically seeded in there from the kms core seed.
            $site->languages()->detach(50);
        }
    }
}