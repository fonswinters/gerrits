<?php

return [

    // All uri's with a 400 error we ignore
    404 => [
        'wp-login',
        'wp-admin',
        'xmlrpc.php',
        'autodiscover',
        '.well-known/apple-app-site-association',
    ],
    // All uri's with a 500 error we ignore
    500 => [
        'autodiscover',
    ],

    // All uri's we ignore
    'all' => [
        'fckeditor'
    ]
];