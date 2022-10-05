<?php

namespace App\Http;

use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\Languages;
use App\Http\Middleware\AliasResolver;
use App\Http\Middleware\OldRouteResolver;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SiteResolver;
use App\Http\Middleware\LocalizedRouteResolver;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\WildcardResolver;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Komma\KMS\Http\Middleware\HSTS;
use Komma\KMS\Http\Middleware\PjaxMiddleware;
use Fruitcake\Cors\HandleCors;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * KOMMA NOTE:
     *
     * We need to put the Language, SiteResolver, AliasResolver and WildcardResolver in here.
     * Because we need to resolve the alias route to a rest route as we use rest route in our application.
     * To get the right alias of a Site we also need to know which Site it is before resolving the alias.
     * In the AliasResolver is a language check to assure that the application is in the right Language of the given (alias) Route.
     * If the route still isn't resolved we check if it's a wildcard card route or a localized route
     * And finally if it still isn't resolved we check if it's a old route and redirect to the current
     *
     * @var array
     */
    protected $middleware = [

        // Laravel Default middleware
//        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
//        ConvertEmptyStringsToNull::class,
//        TrustProxies::class,

        // Komma Default middleware, see note why they are here
        Languages::class,
        SiteResolver::class,
        AliasResolver::class,
        WildcardResolver::class,
        LocalizedRouteResolver::class,
//        OldRouteResolver::class,
        HandleCors::class,

        // Possible gives header to request to verify
        // that this domain is registered @ https://hstspreload.org
        HSTS::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [

        // Should be called when using web routes
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
//            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            'bindings',

            // TODO: REMOVE THIS MIDDLEWARE FROM WEB GROUP
            // This isn't for all the route so shouldn't be called with all the route,
            // I have already made a kms group for these, but because of the route files it couldn't be implemented yet.

        ],

        // Should be called when using api requests
        'api' => [
            'throttle:60,1',
            'bindings',
        ],

        // Should be called when we want to use Pjax requests
        'pjax' => [
            PjaxMiddleware::class,
        ]
    ];
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'bindings' => SubstituteBindings::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        StartSession::class,
        ShareErrorsFromSession::class,
        Authenticate::class,
        AuthenticateSession::class,
        SubstituteBindings::class,
        Authorize::class,
    ];
}