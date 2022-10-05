<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

/**
 * This seeder is especially for sales people.
 * For development, please use the regular DatabaseSeeder.
 * Refer to the readme for more information.
 *
 * Class DatabaseSeeder
 *
 * @see DatabaseSeeder
 */
class DatabaseSeederDemo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Prerequisist seeders
        $this->call(ButtonSeeder::class);

        //Main seeders
        $this->call(DefaultSiteDemoPageSeeder::class);
        $this->call(DefaultSiteDevelopmentPageComponentSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(NewsPostSeeder::class);
        $this->call(EventsSeeder::class);
        $this->call(ReferencesSeeder::class);
        $this->call(ServicepointSeeder::class);
        $this->call(WebsiteConfigSeeder::class);
        //Relation seeders
//        $this->call(ButtonRelationSeeder::class);
        $this->call(DiscoverMorePageRelationSeeder::class);
        $this->call(CorrectRoutesForWildcardModelsSeeder::class);
    }
}
