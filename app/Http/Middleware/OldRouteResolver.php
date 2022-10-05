<?php

namespace App\Http\Middleware;

use App\Posts\Models\PostTranslation;
use Illuminate\Http\Request;

class OldRouteResolver extends AbstractResolver
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Check if route should be excluded from resolving
        if($this->isExcludedFromResolving($request)) return $next($request);

        // Check if route is already resolved
        if($this->isRouteResolved($request)) return $next($request);

        $route = $request->getPathInfo();

        // If last char is / remove it
        if(substr($route, -1) === '/') {
            $route = substr($route, 0, -1);
        }

        // Check if route exists as redirect
        if(key_exists($route, config('redirects'))){

            // Redirect to the found redirect route out the config
            return redirect(config('redirects.'.$route));
        }

        // Bottom half should be customized to the site where it's needed
        // So for know we proceed with the request
        return $next($request);

        // Get the last segment
        $segments = $request->segments();
        $lastSegment = $segments[sizeof($segments) - 1];

        // Try to find the last segment as post item
        if($postTranslation = PostTranslation::where('slug', $lastSegment)->where('active', 1)->first())
        {
            return redirect('/nieuws/'. $lastSegment);
        }

        return $next($request);

    }

}