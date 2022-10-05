<?php

namespace App\Http\Wildcards;


use App\Vacancies\Models\Vacancy;
use App\Vacancies\Models\VacancyTranslation;
use Illuminate\Http\Request;

class VacanciesWildcard implements WildcardInterface
{
    /**
     * @param Request $request
     * @param string $route
     * @param string $tail
     * @return Request
     */
    public function handle(Request $request, string $route, string $tail): Request
    {
        // Check if the first segment is found in the TranslatableModel
        $modelTranslation = VacancyTranslation::where('language_id', app()->getLanguage()->id)
            ->where('slug', $tail)
            ->first();

        // If found then send to the show method
        if($modelTranslation)
        {
            //Set the request URI and the original path
            $request->server->set('REQUEST_URI', 'vacancies/' . $modelTranslation->vacancy_id);
            return $request;
        }

        // Bind filters to the request and set uri to the filter function of the project controller
        $request->merge(['filters' => $tail]);

        //Set the request URI and the original path
        $request->server->set('REQUEST_URI', 'vacancies/filters');

        return $request;
    }

}