<?php


namespace App\Projects;


use App\Base\Service;
use App\Projects\Models\Project;
use Carbon\Carbon;

class ProjectService extends Service
{

    private $today;

    public function __construct()
    {
        $this->today = Carbon::now()->addHour();
        $this->today = $this->today->format('Y-m-d H:i:s');

        parent::__construct();
    }

    public function getAllProjects($pagination = false, $itemsPerPage = 9)
    {
        $projectsQuery = Project::with('translation', 'images')
            ->where('active', 1)
            ->orderBy('lft')
            ->whereHas('translation');

        if($pagination)
        {
            $projects = $projectsQuery->paginate($itemsPerPage);
        }
        else
        {
            $projects = $projectsQuery->get();
        }
        return $projects;
    }


}