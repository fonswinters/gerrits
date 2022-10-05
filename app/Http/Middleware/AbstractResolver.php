<?php
namespace App\Http\Middleware;

use Komma\KMS\Helpers\KommaHelpers;
use Komma\KMS\Sites\SiteServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class AbstractResolver
{
    /** @var SiteServiceInterface  */
    protected $siteService;

    private $excludeRouteStartingWithFromResolving = [];
    private $alreadyLogged = false;

    public function __construct()
    {
        $this->siteService = app(SiteServiceInterface::class);

        // We
        $this->excludeRouteStartingWithFromResolving[] = config('kms.path');
        $this->excludeRouteStartingWithFromResolving[] = '_debugbar';

    }

    /**
     * Returns true if the route is excluded from resolving.
     *
     * @param Request $request
     * @return bool
     */
    public function isExcludedFromResolving(Request $request) : bool
    {
        $firstSegment = $request->segment(1);

        if(!$firstSegment) return false;
        if(!empty($firstSegment) && in_array($firstSegment, $this->excludeRouteStartingWithFromResolving)) {
//            $this->log('First segment ('.$firstSegment.') of path '.$request->path().' was marked as excluded. Ignoring the request');
            return true;
        }
        return false;
    }

    /**
     * Returns true if the route is resolved. False if not
     *
     * @param Request $request
     * @return bool
     */
    public function isRouteResolved(Request $request) : bool
    {
        if(isset($request->resolved) && $request->resolved) return true;
        return false;
    }

    /**
     * Set if the route is resolved or not
     *
     * @param Request $request
     * @return Request
     */
    public function setRouteResolved(Request $request) : Request
    {
        $request->resolved = true;
        $request->resolver = static::class;
        return $request;
    }

    /**
     * @param string $message
     */
    public function log(string $message)
    {
        if(!config('app.debug_middleware_routing')) return;
        if(!$this->alreadyLogged) Log::debug('');
        $this->alreadyLogged = true;
        Log::debug(KommaHelpers::getShortNameFromClass(static::class).': '.$message);
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     */
    abstract public function handle($request, Closure $next);
}