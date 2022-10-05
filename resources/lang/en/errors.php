<?php

return [

    '400' => 'Er is een fout verzoek ingediend.',
    '401' => 'U hebt niet voldoende rechten om deze pagina te bekijken.',
    '403' => 'U hebt geen toestemming tot deze pagina.',
    '404' => 'Deze pagina kon niet worden gevonden.',
    '500' => 'Er is een interne fout opgetreden, sorry voor het ongemak.',
    '501' => 'De server ondersteund deze aanvraag niet.',
    '503' => 'De dienst is tijdelijk niet beschikbaar',

    'default' => 'Oeps er gaat iets fout, sorry voor het ongemak.',

    'errorHeading' => 'Oeps, er ging iets mis!',
    'homeButton' => 'Terug naar home',
    'exceptions' => [
        'route' => [
            \App\Routes\RouteUpdateException::PARENT_HAS_NO_TRANSLATIONS => 'De bovenliggende :type heeft de vertaling voor :lang niet ingevuld.<br>
            Dit moet wel omdat je deze hier wel hebt ingevuld end we anders kunnen geen links naar ze kunnen maken. Hierdoor kunnen nu niet opslaan.<br>
            Je kunt dit probleem als volgt oplossen:<br><br>
                Zet eerst de waarde van het invoerveld ":field" op "/" en druk op opslaan.<br>
                Ga naar de bovenliggende :type en vul het taal tabblad :lang in en sla dat op.<br>
                Vervolgens kun je hier het invoerveld ":field" instellen op de gewenste waarde en het opslaan.
            ',
        ]
    ]
];
