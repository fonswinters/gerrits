<?php


namespace App\TeamMembers;

use App\Base\Service;
use App\TeamMembers\Models\TeamMember;

final class TeamMemberService extends Service
{
    private function baseQuery()
    {
        return TeamMember::where('active', 1)
            ->where('lft', '!=', 1)
            ->orderBy('lft','asc')
            ->with(['translation', 'images']);
    }

    public function getAllTeamMembers()
    {
        return $this->baseQuery()->get();
    }
}