<?php


namespace App\Vacancies;


use App\Base\Service;
use App\Vacancies\Models\Vacancy;
use Carbon\Carbon;

class VacancyService extends Service
{

    private $today;

    public function __construct()
    {
        $this->today = Carbon::now()->addHour();
        $this->today = $this->today->format('Y-m-d H:i:s');

        parent::__construct();
    }

    public function getAllVacancies($pagination = false, $itemsPerPage = 9)
    {
        $vacancies = Vacancy::with('translation', 'images')
            ->where('active', 1)
            ->orderBy('lft')
            ->whereHas('translation');

        if($pagination)
        {
            $vacancies = $vacancies->paginate($itemsPerPage);
        }
        else
        {
            $vacancies = $vacancies->get();
        }
        return $vacancies;
    }

    /**
     * Get amount of vacancies
     *
     * @param  int  $amount
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getAmountOfVacancies(int $amount)
    {
        return Vacancy::with('translation', 'images')
            ->where('active', 1)
            ->orderBy('lft')
            ->whereHas('translation')
            ->take($amount)
            ->get();
    }


}