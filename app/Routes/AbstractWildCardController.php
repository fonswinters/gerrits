<?php

namespace App\Routes;


use App\Base\Controller;

abstract class AbstractWildCardController extends Controller
{
    /**
     * Returns a HandlesWildcardsInterface that can resolve the given wildcard path for us or null if there isn't an interface that can resolve it for us.
     *
     * @param string $path the wildcard path.
     * @return null
     */
    protected function getWildcardResolverController(string $path)
    {
        $wildCardControllers = config('route.wildcardResolverControllers');

        foreach($wildCardControllers as $wildCardController)
        {
            if(!method_exists($wildCardController, 'resolvesWildcardPath')) {
                \Log::error($wildCardController.' is not a wildcard service. Please make sure it implements the HandlesWildCardsInterface or remove it from the route config file\'s wildcardServices array');
                return null;
            }

            /** @var HandlesWildcardsInterface $wildCardController */
            if($wildCardController::resolvesWildcardPath($path))
            {
                return $wildCardController;
            }
        }
    }
};