<?php

namespace App\Http\Wildcards;

use App\Events\Models\EventTranslation;
use Illuminate\Http\Request;

class EventsWildcard implements WildcardInterface
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
        $modelTranslation = EventTranslation::where('language_id', app()->getLanguage()->id)
            ->where('slug', $tail)
            ->first();

        // If found then send to the show method
        if($modelTranslation)
        {
            //Set the request URI and the original path
            $request->server->set('REQUEST_URI', 'events/' . $modelTranslation->event_id);
            return $request;
        }

        // Bind filters to the request and set uri to the filter function of the event controller
        $request->merge(['filters' => $tail]);

        //Set the request URI and the original path
        $request->server->set('REQUEST_URI', 'events/filters');

        return $request;
    }

}