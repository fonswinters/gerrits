<?php

namespace App\Http\Wildcards;

use App\Services\Models\ServiceTranslation;
use Illuminate\Http\Request;

class ServicesWildcard implements WildcardInterface
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
        $modelTranslation = ServiceTranslation::where('language_id', app()->getLanguage()->id)
            ->where('slug', $tail)
            ->first();

        // If found then send to the show method
        if($modelTranslation)
        {
            //Set the request URI and the original path
            $request->server->set('REQUEST_URI', 'services/' . $modelTranslation->service_id);
            return $request;
        }

        // Bind filters to the request and set uri to the filter function of the service controller
        $request->merge(['filters' => $tail]);

        //Set the request URI and the original path
        $request->server->set('REQUEST_URI', 'services/filters');

        return $request;
    }

}