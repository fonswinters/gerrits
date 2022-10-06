<?php

return [

    /*
    |--------------------------------------------------------------------------
    | KMS App Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to display the name of the application within the UI
    | or in other locations. Of course, you're free to change the value.
    |
    */

    'name' => 'Komma management system',

    'languages' => [
        'nl',
//        'de',
//        'en'
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'nl',

    /*
    |--------------------------------------------------------------------------
    | KMS Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where KMS will be accessible from. Feel free to
    | change this path to anything you like. Note that this URI will not
    | affect Kms's internal API routes which aren't exposed to users.
    |
    */

    'path' => 'kms',


    /*
    |--------------------------------------------------------------------------
    | TextArea Style formats
    |--------------------------------------------------------------------------
    |
    | Style formats setting for when the default is just not enough.
    | This way we can customize this for each client. By calling the
    | "setStyleFormatFromConfig" on the TextArea Attribute you can use it.
    | You can also change the default config key to use multiple style formats.
    |
    | See link, to know how to create style formats
    | https://www.tiny.cloud/docs/configure/editor-appearance/#style_formats
    | https://www.tiny.cloud/docs/general-configuration-guide/filter-content/#rollyourstyleformats
    |
    */

    'text_area_style_formats' => [
        [
            'title' => 'Paragraaf',
            'block' => 'p',
        ],
        [
            'title' => 'H1',
            'block' => 'h1',
        ],
        [
            'title' => 'H2',
            'block' => 'h2',
        ],
        [
            'title' => 'H3',
            'block' => 'h3',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | TinyMCE Color for picker
    |--------------------------------------------------------------------------
    */
    'tinymce_color_map' => [
        '#2DC26B', 'Green',
        '#F1C40F', 'Yellow',
        '#E03E2D', 'Red',
        '#B96AD9', 'Purple',
        '#3598DB', 'Blue',
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Path
    |--------------------------------------------------------------------------
    |
    | This is the path where the KMS resources will published the files to.
    | Also the kms_asset helper function will populated this path.
    |
    */

    'published_path' => 'vendor/kms',

    'employees' => [
//        [
//            'image' => 'claire_l.png',
//            'name' => 'Claire Lardenois',
//            'function' => 'Projectmanager',
//            'mail' => 'claire@komma.nl',
//        ],
        [
            'image' => 'fons_w.png',
            'name' => 'Fons Winters',
            'function' => 'Technisch consultant',
            'mail' => 'info@winters-online.nl',
        ],
//        [
//            'image' => 'marielle_v.png',
//            'name' => 'Mariëlle Vreeken ',
//            'function' => 'Projectmanager',
//            'mail' => 'marielle@komma.nl',
//        ],
//        [
//            'image' => 'arnoud_j.png',
//            'name' => 'Arnoud Jacobs',
//            'function' => 'Designer',
//            'mail' => 'arnoud@komma.nl',
//        ],
//        [
//            'image' => 'mike_s.png',
//            'name' => 'Mike van der Sanden',
//            'function' => 'Chef kwaliteit',
//            'mail' => 'mike@komma.nl',
//        ],
//        [
//            'image' => 'pascal_l.png',
//            'name' => 'Pascal Lemmen',
//            'function' => 'Webdeveloper',
//            'mail' => 'pascal@komma.nl',
//        ],
//        [
//            'image' => 'rob_h.png',
//            'name' => 'Rob Hoeben',
//            'function' => 'Webdeveloper',
//            'mail' => 'rob@komma.nl',
//        ],
//        [
//            'image' => 'tim_l.png',
//            'name' => 'Tim Lammers',
//            'function' => 'Operationeel directeur',
//            'mail' => 'tim@komma.nl',
//        ],
//        [
//            'image' => 'twan_l.png',
//            'name' => 'Twan Lammers',
//            'function' => 'Online Marketeer',
//            'mail' => 'twan@komma.nl',
//        ],
//        [
//            'image' => 'vincent_l.png',
//            'name' => 'Vincent van Lierop',
//            'function' => 'Commercieel directeur',
//            'mail' => 'vincent@komma.nl',
//        ],
//        [
//            'image' => 'stef_b.png',
//            'name' => 'Stef Bogers',
//            'function' => 'Commercieel directeur',
//            'mail' => 'stef@komma.nl',
//        ],
//        [
//            'image' => 'toon_s.png',
//            'name' => 'Toon Schuurmans',
//            'function' => 'Webdeveloper',
//            'mail' => 'toon@komma.nl',
//        ],
    ],

];
