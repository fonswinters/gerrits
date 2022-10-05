<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HSTS
 *
 * Enables HTTPS Strict Transport Security.
 * It prevents http downgrade attacks and cookie hijacking.
 *
 * Make sure you understand the removal process and knockout entries completely.
 * Also make sure you redirect all http requests to https via for example your .htaccess or web.config files
 * as this middleware does not do that for you.
 *
 * @see https://hstspreload.org/
 * @see https://hstspreload.org/removal/
 * @see https://blog.mozilla.org/security/2012/11/01/preloading-hsts/ knockout entries
 *
 * @package App\Http\Middleware
 */
class HSTS
{
    //This is the value at which preloading can be enabled after meeting the other requirements too.
    const MINIMUM_MAX_AGE_VALUE = 31536000; //1 Year. Must match the value stated at hstspreload.org

    const STAGED_MAX_AGE_VALUES = [
        self::MINIMUM_MAX_AGE_VALUE,
        2592000,   //1 Month
        604800,    //1 Week
        300,       //5 Minutes
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!config('hsts.enabled', false)) return $next($request);
        /** @var Response $response */
        $response = $next($request); //Pass the request to other middleware and the application and return the response.

        $response = $this->addHSTSHeaderToResponse($response);

        return $response;
    }

    /**
     * Builds and adds the HSTS Header to the response.
     * Telling browsers to load all uri's from this website via HTTPS and not HTTP
     *
     * @param Response $response
     * @return Response
     */
    public function addHSTSHeaderToResponse(Response $response)
    {
        $maxAge = self::STAGED_MAX_AGE_VALUES[config('hsts.max_age_stage', 3)];

        $headerName = 'Strict-Transport-Security';
        $headerOptions  = 'max-age='.$maxAge.'; includeSubDomains';

        if(config('hsts.preload', false)) $headerOptions .= '; preload';

        $response->header($headerName, $headerOptions);

        return $response;
    }
}
