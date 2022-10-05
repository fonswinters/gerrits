<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Enable HSTS
    |--------------------------------------------------------------------------
    | Enables a HSTS header which causes everything on your website to be
    | forced to be loaded via HTTPS instead of HTTP.
    | HSTS stands for Https Strict Transport Security. See the HSTS middleware
    | for the implementation. Allongside this measure you also must configure http
    | to https redirection from your servers configuration. For example in your
    | .htaccess and web.config files.
    |
    */
    'enabled' => env('HSTS_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | HSTS Preloading
    |--------------------------------------------------------------------------
    |
    | BE VERY CAREFUL WITH THIS OPTION!!!!!!
    | Whether or not to include the preload directive in the HSTS header.
    | This flag indicates that the websites wishes to be included at the HSTS
    | preload list.
    |
    | When a website is on that list, the browser wil force the website to
    | be visited by HTTPS only and not by HTTP. The preload list is embedded
    | in most modern browsers and you will be put on it or off it at the next
    | update from that browser. Typically 6 to 12 weeks.
    |
    | If you don't or do not yet serve everything on your website via HTTPS
    | this option can cause your website to get in an unusable state for
    | at least 6 to 12 weeks after you've marked the domain to be removed
    | from the list. Turn on this option if HSTS is on for a while already,
    | not using problems. Please check https://hstspreload.org/ for more info.
    |
    | Also notice that this option, when set to true, overrides the
    | deployment_stage option effects.
    */
    'preload' => env('HSTS_PRELOAD', false),

    /*
    |--------------------------------------------------------------------------
    | Max age stage.
    |--------------------------------------------------------------------------
    |
    | The max age value in the header determines how long the browser may cache
    | the HSTS header before requesting it again. When enabling HSTS you first
    | leave it on 3 for 5 minutes. Then you test your site and solve any HTTP
    | request issues. When they are solved or no issues arise you put it on
    | stage 2 for a week and fix any HTTP request issues like in stage 3.
    | Then you do stage 1 for a month. After stage one is completed you can
    | enable the preload option. But make sure you know what that does first.
    */
    'max_age_stage' => env('HSTS_MAX_AGE_STAGE', 3),
];