<?php


namespace App\Composers;


use App\Vacancies\Models\Vacancy;
use Illuminate\View\View;

class VacancyListComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $vacancies = vacancy::where('active', '>', 0)->whereHas('translation')->orderBy('lft')->get();
        $view->with(['vacancies' => $vacancies]);
    }
}