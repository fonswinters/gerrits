<?php
namespace Database\Seeders;

use App\TeamMembers\Models\TeamMember;
use App\TeamMembers\Models\TeamMemberTranslation;
use Komma\KMS\Globalization\Languages\Models\Language;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the seed
     */
    public function run()
    {
        //Get the languages
        $languages = Language::whereIn('iso_2', ['nl', 'en'])->get(['id', 'iso_2']);

        //Create the root page
        $siteRootTeamMember = new TeamMember(['active' => 0]);
        $siteRootTeamMember->makeRoot();

        $this->createTeamMember1($siteRootTeamMember, $languages);
        $this->createTeamMember2($siteRootTeamMember, $languages);
        $this->createTeamMember3($siteRootTeamMember, $languages);
    }

    /**
     * @param TeamMember $siteRootTeamMember
     * @param Collection $languages
     * @return TeamMember
     */
    private function createTeamMember1(TeamMember $siteRootTeamMember, Collection $languages): TeamMember
    {
        //Create the service itself...
        $teamMember = new TeamMember([
            'active'      => 1,
            'name'        => 'Laquita Elliot',
            'email'       => 'laquita@company.fake',
            'linkedinurl' => 'https://www.linkedin.com/',
        ]);

        $teamMember->makeLastChildOf($siteRootTeamMember);
        $teamMember->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $teamMemberTranslation = new TeamMemberTranslation([
            'function'            => 'Medewerker',
        ]);
        $teamMemberTranslation->language()->associate($language);
        $teamMemberTranslation->translatable()->associate($teamMember);
        $teamMemberTranslation->save();

        return $teamMember;
    }

    /**
     * @param TeamMember $siteRootTeamMember
     * @param Collection $languages
     * @return TeamMember
     */
    private function createTeamMember2(TeamMember $siteRootTeamMember, Collection $languages): TeamMember
    {
        //Create the service itself...
        $teamMember = new TeamMember([
            'active'      => 1,
            'name'        => 'Tua Manuera',
            'email'       => 'tua@company.fake',
        ]);

        $teamMember->makeLastChildOf($siteRootTeamMember);
        $teamMember->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $teamMemberTranslation = new TeamMemberTranslation([
            'function'            => 'HR medewerker',
        ]);
        $teamMemberTranslation->language()->associate($language);
        $teamMemberTranslation->translatable()->associate($teamMember);
        $teamMemberTranslation->save();

        return $teamMember;
    }

    /**
     * @param TeamMember $siteRootTeamMember
     * @param Collection $languages
     * @return TeamMember
     */
    private function createTeamMember3(TeamMember $siteRootTeamMember, Collection $languages): TeamMember
    {
        //Create the service itself...
        $teamMember = new TeamMember([
            'active'      => 1,
            'name'        => 'Shadrias Pearson',
            'email'       => 'shadrias@company.fake',
        ]);

        $teamMember->makeLastChildOf($siteRootTeamMember);
        $teamMember->save();

        //...the Dutch translation
        $language = $languages->where('id', '104')->first();
        $teamMemberTranslation = new TeamMemberTranslation([
            'function'            => 'Creatief directeur',
        ]);
        $teamMemberTranslation->language()->associate($language);
        $teamMemberTranslation->translatable()->associate($teamMember);
        $teamMemberTranslation->save();

        return $teamMember;
    }

}









