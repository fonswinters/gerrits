<?php
namespace App\Routes;


use App\Pages\Models\Page;
use App\Routes\Models\RedirectRoute;
use App\Routes\Models\Route;
use Komma\KMS\Sites\Models\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

abstract class AbstractRouteService
{
    /** @var string $routeClass The regular route class*/
    private static $routeClass;

    /** @var string $redirectRouteClass The route class used for http redirects */
    private static $redirectRouteClass;

    public function __construct()
    {
    }

    /**
     * Creates or updates a route for a model.
     *
     * @param Model $model
     * @return Model
     */
    abstract public function createOrUpdateRoutesForModelsTranslationsIfChanged(Model $model): Model;

    abstract public function getRouteByAlias(string $alias, Site $site, bool $mustBeActive = false, string $routeInterfaceNameToReturn = null): ?Model;

    /**
     * @return string|Builder|Route
     */
    public static function getRouteClassName(): string
    {
        if(!self::$routeClass) throw new \RuntimeException('Error: The route class was not set.');
        return self::$routeClass;
    }

    /**
     * @param string $routeClassName
     * @return void
     */
    public static function setRouteClassName($routeClassName)
    {
        if(!self::checkIfItIsARouteInstance($routeClassName)) throw new \RuntimeException("The implementation of AbstractRouteService '".static::class."' expected a RouteModel class name but got a non existing one.");

        self::$routeClass = $routeClassName;
    }

    /**
     * @return string|Builder|RedirectRoute
     */
    public static function getRedirectRouteClassName(): string
    {
        if(!self::$redirectRouteClass) throw new \RuntimeException('Error: The redirect route class was not set');
        return self::$redirectRouteClass;
    }

    /**
     * @param string $redirectRouteClass
     */
    public static function setRedirectRouteClassName(string $redirectRouteClass)
    {
        if(!self::checkIfItIsARedirectRouteClass($redirectRouteClass)) throw new \RuntimeException("The implementation of an AbstractRouteService '".static::class."' expected a RedirectRouteModelInterface instance but got a non existing one.");

        self::$redirectRouteClass = $redirectRouteClass;
    }

    /**
     * Checks if the given class instance is a Route class. That is a class who's implementing the RouteModelInterface.
     * Returns true if it is. False otherwise
     *
     * @see RouteModelInterface
     * @param object|string $object The class to check
     * @return bool
     */
    public static function checkIfItIsARouteInstance($object)
    {
        if(!is_object($object)) {
            if(class_exists($object))
                $object = new $object;
            else
                return false;
        }
        if(!is_a($object, RouteModelInterface::class)) return false;
        return true;
    }

    /**
     * Checks if the given class instance is a Redirect route class. That is a class who's implementing the RedirectRouteModelInterface.
     * Returns true if it is. False otherwise
     *
     * @see RedirectRouteModelInterface
     * @param object|string $object The class to check
     * @return bool
     */
    public static function checkIfItIsARedirectRouteClass($object)
    {
        if(!is_object($object)) {
            if(class_exists($object))
                $object = new $object;
            else
                return false;
        }
        if(!is_a($object, RedirectRouteModelInterface::class)) return false;
        return true;
    }
}