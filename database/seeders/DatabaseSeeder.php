<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

/***
 * This seeder is used for developers.
 * When deploying to basic.komma.nl, please use DatabaseSeederDemo instead.
 * Refer to the readme for more information.
 *
 * Class DatabaseSeeder
 * @see DatabaseSeederDemo
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Prerequisist seeders must run first
        $this->call(ButtonSeeder::class);
        $this->call(ServicepointSeeder::class);

        //Main seeders
        $this->call(DefaultSiteDevelopmentPageSeeder::class);
        $this->call(DefaultSitePageComponentSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(NewsPostSeeder::class);
        $this->call(EventsSeeder::class);
        $this->call(ReferencesSeeder::class);
        $this->call(VacancySeeder::class);
        $this->call(VacancyProcessSeeder::class);
        $this->call(TeamMemberSeeder::class);
        $this->call(WebsiteConfigSeeder::class);
        $this->call(RemoveGermanLanguageFromSiteLanguages::class);

        //Relation seeders
//        $this->call(ButtonRelationSeeder::class);
        $this->call(DiscoverMorePageRelationSeeder::class);
        $this->call(CorrectRoutesForWildcardModelsSeeder::class);
    }
}
