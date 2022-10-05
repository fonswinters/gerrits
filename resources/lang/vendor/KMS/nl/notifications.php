<?php
return [
    'customer_registered' => [
        'subject'               => 'Nieuwe klant - :first_name :last_name',
        'mail_greeting'         => 'Hallo!',
        'mail_intro'            => 'Een nieuwe klant heeft zich geregistreerd:',
        'mail_action'           => 'Naar het beheerpaneel',
        'customer_data'         => 'Het betreft klant :first_name :last_name',
        'name'                  => 'Naam',
        'email'                 => 'E-mailadres',
        'telephone'             => 'Telefoonnummer',
        'mobile'                => 'Mobiel nummer',
    ],

    'customer_set_password' => [
        'subject'               => 'Je wachtwoord instellen.',
        'mail_greeting'         => 'Hallo :first_name,',
        'mail_intro'            => 'Bedankt voor je registratie in onze site. Klik op de onderstaande knop om een wachtwoord in te stellen en je account te activeren. Daarna kun je de webshop verder gebruiken met je eigen account.',
        'mail_action'           => 'Wachtwoord instellen',
        'mail_outro'            => 'Werkt de knop niet? Kopieer en plak deze link dan in je browser:',
        'set'                   => 'Wachtwoord instellen',
    ],

    'customer_reset_password' => [
        'subject'               => 'Wachtwoord resetten',
        'mail_greeting'         => 'Hallo :first_name,',
        'mail_intro'            => 'We hebben een verzoek ontvangen om je wachtwoord te resetten. Door op onderstaande knop te klikken, kun je een nieuw wachtwoord kiezen. Daarna kun je weer inloggen met je nieuwe accountgegevens. Heb je dit verzoek niet ingediend? Dan mag je deze deze e-mail als niet verzonden beschouwen.',
        'mail_action'           => 'Wachtwoord resetten',
        'mail_action_text'      => 'Werkt de knop niet? Kopieer en plak deze link dan in je browser:',
        'set'                   => 'Wachtwoord resetten',
    ],

    'footer' => 'Komma - &copy; :year'
];