<?php


namespace App\VacancyProcess;


use App\Base\Service;
use App\VacancyProcess\Models\VacancyProcess;
use Carbon\Carbon;

final class VacancyProcessService extends Service
{

    private $today;

    public function __construct()
    {
        $this->today = Carbon::now()->addHour();
        $this->today = $this->today->format('Y-m-d H:i:s');

        parent::__construct();
    }

    public function getAllVacancyProcesses()
    {
        $referencesQuery = VacancyProcess::with('translation')
            ->where('id', '!=',  1)
            ->orderBy('lft');

        return $referencesQuery->get();
    }


}