<?php
namespace Database\Seeders;

use Komma\KMS\Globalization\Languages\Models\Language;
use App\Projects\Models\Project;
use App\Projects\Models\ProjectTranslation;
use App\Site\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ProjectSeeder extends Seeder
{
    /**
     * Run the seed
     */
    public function run()
    {
        //Get the default site
        $site = Site::where('slug', '=', 'default')->first();

        //Get the languages
        $languages = Language::whereIn('iso_2', ['nl', 'en'])->get(['id', 'iso_2']);

        //Create the root page
        $siteRootProject = new Project(['active' => 0]);
        $siteRootProject->makeRoot();

        $weddingEindhovenProject      = $this->createWeddingEindhovenProject($siteRootProject, $site, $languages);
        $cranendonckOpenAirProject     = $this->createCranendonckOpenAirProject($siteRootProject, $site, $languages);
        $budelCarnivalProject         = $this->createBudelCarnivalProject($siteRootProject, $site, $languages);
    }


    /**
     * @param Project $rootService
     * @param Site $site
     * @param Collection $languages
     * @return Project
     */
    private function createWeddingEindhovenProject(Project $rootService, Site $site, Collection $languages): Project
    {
        //Create the service itself...
        $project = new Project([
            'active'        => 1,
        ]);

        $project->site()->associate($site);
        $project->makeLastChildOf($rootService);
        $project->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $projectTranslation = new ProjectTranslation([
            'slug'                => 'bruiloft-eindhoven',
            'name'                => 'Bruiloft Eindhoven',
            'hero_title'          => 'Een prachtige dag gevuld met goed eten',
            'hero_description'    => '<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur suscipit suscipit tellus. In ut quam vitae odio lacinia tincidunt. Nunc egestas, augue at pellentesque laoreet, felis eros vehicula leo, at malesuada velit leo quis pede. Nullam tincidunt adipiscing enim. In turpis. Pellentesque commodo eros a enim. Etiam ut purus mattis mauris sodales aliquam. Nam eget dui. Quisque ut nisi.</p>',
            'hero_active'         => 1,
        ]);
        $projectTranslation->language()->associate($language);
        $projectTranslation->translatable()->associate($project);
        $projectTranslation->save();

        return $project;
    }

    /**
     * @param Project $rootService
     * @param Site $site
     * @param Collection $languages
     * @return Project
     */
    private function createCranendonckOpenAirProject(Project $rootService, Site $site, Collection $languages): Project
    {
        //Create the project itself...
        $project = new Project([
            'active'        => 1,
        ]);

        $project->site()->associate($site);
        $project->makeLastChildOf($rootService);
        $project->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $projectTranslation = new ProjectTranslation([
            'slug'                => 'cranendonck-open-air',
            'name'                => 'Cranendonck Open Air',
            'hero_title'          => 'Good Music, Good Food, Good Vibe',
            'hero_description'    => '<p>Shankle meatball pastrami turducken short ribs salami drumstick alcatra kevin tongue landjaeger. Leberkas ribeye meatloaf jerky, cow fatback beef ribs doner turducken. Jerky t-bone turkey corned beef sausage pork belly. Pig leberkas kielbasa, ham alcatra ground round cupim jowl burgdoggen. Beef turducken ball tip, pastrami beef ribs salami meatloaf.</p>',
            'hero_active'         => 1,
        ]);
        $projectTranslation->language()->associate($language);
        $projectTranslation->translatable()->associate($project);
        $projectTranslation->save();

        return $project;
    }

    /**
     * @param Project $rootService
     * @param Site $site
     * @param Collection $languages
     * @return Project
     */
    private function createBudelCarnivalProject(Project $rootService, Site $site, Collection $languages): Project
    {
        //Create the project itself...
        $project = new Project([
            'active'        => 1,
        ]);

        $project->site()->associate($site);
        $project->makeLastChildOf($rootService);
        $project->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $projectTranslation = new ProjectTranslation([
            'slug'                => 'budel-kermis',
            'name'                => 'Budel Kermis',
            'hero_title'          => 'BBQ verzorgd voor de hele kermis',
            'hero_description'    => '<p>Shankle meatball pastrami turducken short ribs salami drumstick alcatra kevin tongue landjaeger. Leberkas ribeye meatloaf jerky, cow fatback beef ribs doner turducken. Jerky t-bone turkey corned beef sausage pork belly. Pig leberkas kielbasa, ham alcatra ground round cupim jowl burgdoggen. Beef turducken ball tip, pastrami beef ribs salami meatloaf.</p>',
            'hero_active'         => 1,
        ]);
        $projectTranslation->language()->associate($language);
        $projectTranslation->translatable()->associate($project);
        $projectTranslation->save();

        return $project;
    }
}









