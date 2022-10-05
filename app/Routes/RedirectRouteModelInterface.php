<?php
namespace App\Routes;

use Illuminate\Database\Eloquent\Relations\MorphTo;

interface RedirectRouteModelInterface
{
    const HTTPStatusCode_301 = 301; // HTTP/1.0 Permanent, cachable. May transform a GET to a POST and vice versa while redirecting
    const HTTPStatusCode_302 = 302; // HTTP/1.0 Temporary, not cached by default. May transform a GET to a POST and vice versa while redirecting
    const HTTPStatusCode_303 = 303; // HTTP/1.1 Never cachable. Redirect is always a GET
    const HTTPStatusCode_307 = 307; // HTTP/1.1 Temporary, not cached by default. Request type does not change while redirecting
    const HTTPStatusCode_308 = 308; // HTTP/1.1 Permanent, cached by default. Request type does not change while redirecting

    /**
     * @mixin \Eloquent
     * @package App\Routes
     */
    public function routable(): MorphTo;

    /**
     * Check if the passed redirect code is really a redirect code that is defined as a constant in this class.
     *
     * @param int $redirectCode
     * @param bool $strict If true it will only consider a redirect code as valid if it is an int. If it for example is a numeric string it won't consider it valid
     * @return bool Returns true if the redirect code is a valid one, false otherwise
     */
    public static function isValidRedirectCode(int $redirectCode, $strict = false);

    /**
     * Returns an array containing integers representing the defined redirect codes.
     *
     * @return int[]
     */
    public static function getAllRedirectCodes();
}