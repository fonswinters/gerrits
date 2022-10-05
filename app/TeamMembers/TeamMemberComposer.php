<?php namespace App\TeamMembers;

class TeamMemberComposer
{
    /** @var TeamMemberService $teamMemberService */
    private $teamMemberService;

    public function __construct()
    {
        $this->teamMemberService = app()->make(TeamMemberService::class);
    }

    public function getAll($view)
    {
        $view->with('composedTeamMembers', $this->teamMemberService->getAllTeamMembers());
    }
}