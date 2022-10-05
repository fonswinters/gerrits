<?php

namespace App\Http\Wildcards;

use Illuminate\Http\Request;

interface WildcardInterface
{
    /**
     * @param Request $request
     * @param string $route
     * @param string $tail
     * @return Request
     */
    public function handle(Request $request, string $route, string $tail): Request;
}