<?php
namespace App\Routes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Query\Builder;

/**
 * Indicates that an eloquent model has a routes method wich resolves to RoutableInterfaces
 *
 * Interface HasRoutesInterface
 *
 * @mixin \Eloquent
 * @package App\Kms\Core
 */
interface HasRoutesInterface
{
    /**
     * @return MorphOne returns a relation that resolves to RouteModelInterface
     * @see RouteModelInterface
     */
    public function route(): MorphOne;

    /**
     * @return MorphMany returns a relation that resolves to RedirectRouteModelInterface
     * @see RedirectRouteModelInterface
     */
    public function redirectRoutes(): MorphMany;
}