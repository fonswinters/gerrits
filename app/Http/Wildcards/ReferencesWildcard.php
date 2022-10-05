<?php

namespace App\Http\Wildcards;

use App\References\Models\ReferenceTranslation;
use Illuminate\Http\Request;

class ReferencesWildcard implements WildcardInterface
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
        $modelTranslation = ReferenceTranslation::where('language_id', app()->getLanguage()->id)
            ->where('slug', $tail)
            ->first();

        // If found then send to the show method
        if($modelTranslation)
        {
            //Set the request URI and the original path
            $request->server->set('REQUEST_URI', 'references/' . $modelTranslation->reference_id);
            return $request;
        }

        // Bind filters to the request and set uri to the filter function of the project controller
        $request->merge(['filters' => $tail]);

        //Set the request URI and the original path
        $request->server->set('REQUEST_URI', 'references/filters');

        return $request;
    }

}