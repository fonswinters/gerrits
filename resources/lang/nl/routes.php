<?php declare(strict_types=1);
return [
    //Array structure: Named route name => Translation.
    //Do not add route parameters like this: {token}. They won't be replaced as you might expect.
    //When you use the localized_route helper, you may pass parameters.
    //When you do, do this in a numeric (non associative) manner in the same order as they appear in the named route.

    //Translations for authentication routes
    'site.login' => 'inloggen',
    'site.logout' => 'uitloggen',
    'site.password.request' => 'wachtwoord/vergeten',
    'site.password.email' => 'wachtwoord/email',
    'site.password.reset' => 'wachtwoord/reset',
    'site.password.request_set' => 'wachtwoord/maken',
    'site.password.set' => 'wachtwoord/instellen',
    'site.register' => 'registereren',
    'site.registered' => 'geregistreerd',

    // Translations for success routes
    'contact.success' => 'contact/thanks',
    'vacancy.success' => 'vacature/bedankt/{vacancy}',
    'event.success' => 'event/{eventSlug}/bedankt',

    //Translations for shopping cart routes
    'shoppingcart' => 'winkelwagen'
];