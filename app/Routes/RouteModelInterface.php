<?php

namespace App\Routes;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Interface RouteModelInterface
 *
 * @mixin \Eloquent
 * @package App\Routes
 */
interface RouteModelInterface
{
    public function routable(): MorphTo;
}