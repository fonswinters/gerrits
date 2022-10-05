<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Local Domains
    |--------------------------------------------------------------------------
    |
    | The url hosts of sites when the environment is set on local.
    | Note: we don't use the localhost:8001 because this is commonly used for
    | running the npm watch, so to prevent complication the second site is
    | pointed from localhost:8002.
    |
    | The key is the slug of the desired site.
    |
    */
    'local' => [
        'default' => ['localhost:8000','localhost:8001','localhost:8888', '127.0.0.1:8000', '127.0.0.1:8888', '192.168.60.104:8888'],
        'example' => ['localhost:8002', '127.0.0.1:8888'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing Domains
    |--------------------------------------------------------------------------
    |
    | The url hosts of sites when the environment is set on testing.
    | Note: we don't use the localhost:8001 because this is commonly used for
    | running the npm watch, so to prevent complication the second site is
    | pointed from localhost:8002.
    |
    | The key is the slug of the desired site.
    |
    */
    'testing' => [
        'default' => ['localhost:8000','localhost:8001','localhost:8888', '127.0.0.1:8000', '192.168.60.104:8888'],
        'example' => ['localhost:8002', '127.0.0.1:8888'],
    ],

    'testing_ci' => [
        'default' => ['localhost:8000','localhost:8001','localhost:8888', '127.0.0.1:8000', '192.168.60.104:8888'],
        'example' => ['localhost:8002', '127.0.0.1:8888'],
    ],


    /*
    |--------------------------------------------------------------------------
    | Development Domains
    |--------------------------------------------------------------------------
    |
    | The url hosts of sites when the environment is set on development.
    | NOTE: we have to set the pointer from the sub site in the panel
    | to use the folder of the main site, so the multi site functionality
    | will work.
    |
    */
    'development' => [
        'default' => ['demo.komma.nl'],
        'example' => ['example.komma.nl'],
    ],


    /*
    |--------------------------------------------------------------------------
    | Live Domains
    |--------------------------------------------------------------------------
    |
    | The url hosts of sites when the environment is set on live.
    | Note: we have to set the pointer from the sub site in the panel
    | to use the folder of the main site, so the multi site functionality
    | will work.
    |
    */
    'production' => [

        'default' => ['demo.komma.nl', 'shop.komma.nl'],
        'example' => ['example.komma.nl'],

        // Normally these are the settings for the live website
        // but because these sites are our own sub site it's the same as development
//        'anvil'                  => ['anvil-industries.nl', 'anvil-industries.com', 'anvil-industries.eu', 'thepaverspaning.nl'],
//        'lacom-machinefabriek'   => ['lacom.nl', 'lacommachinefabriek.nl', 'lc-hydraulics.nl', 'lc-hydraulics.eu', 'lc-hydraulics.com'],

    ],
];