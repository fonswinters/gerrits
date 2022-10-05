<?php


use App\Components\ComponentTypes;

return [
    'types' => [
        ComponentTypes::CONTENT_PERSONAL => 'Tekst | Contactpersoon',
        ComponentTypes::CONTENT_SLIDER => 'Tabbladen',
        ComponentTypes::USP => 'Lijst | Afbeelding',
        ComponentTypes::DOWNLOADS => 'Downloads | Contactpersoon',
        ComponentTypes::TEXT_IMAGE_BUTTON => 'Tekst | Afbeelding',
        ComponentTypes::FEATURED_VACANCIES => 'Aangeboden vacatures',

        ComponentTypes::DOUBLE_USP => 'Dubbele lijst',
        ComponentTypes::VACANCY_PROCESS_PERSONAL => 'Sollicitatieproces',

        //Override / add type translations here
        ComponentTypes::TEXT_IMAGE => 'Text + Afbeelding',
        ComponentTypes::DOUBLE_TEXT => 'Dubbele text',
        ComponentTypes::VIDEO => 'Youtube Video',
        ComponentTypes::TEXT => 'Tekst',
        ComponentTypes::IMAGE => 'Afbeelding',
        ComponentTypes::DOUBLE_IMAGE => 'Afbeelding + Afbeelding',
        ComponentTypes::QUOTE => 'Citaat',

        ComponentTypes::TITLE_TEXT_IMAGE => 'Titel + Tekst + Afbeelding',
//        ComponentTypes::FEATURED_PRODUCTS => 'Uitgelichte producten',
        ComponentTypes::HERO => 'Hero Afbeeldingen',
        ComponentTypes::EVENT => 'Evenement',
        ComponentTypes::TEAM => 'Ons Team',
    ],
    'contact' => 'Contactpersoon',
    'list' => 'Lijst',
    'line' => 'Regel',
    'swap_download_contact' => 'Download en contactpersoon omwisselen',
    'swap_double_image' => 'Omwisselen Afbeeldingen',
    'options' => 'Opties',
    'choose_a_category' => 'Kies een categorie',
    'title_above_category' => 'Tekst boven de categorie',
    'choose_featured_products' => 'Toon de volgende producten op deze pagina',
    'title_above_products' => 'Titel boven de producten',
    'images' => 'Afbeeldingen',
    'button' => 'Knop',
    'first_button' => 'Eerste knop',
    'ghost_button' => 'Tweede knop',
    'create_buttons_first' => 'Maak eerst 1 of meerdere knoppen om deze hier toe te voegen',
    'max_amount' => 'Maximaal aantal tonen',
    'max_amount_team_members' => 'Maximaal aantal teamleden tonen',
    'team_title' => 'Titel boven teamleden',
    'left' => 'Links',
    'right' => 'Rechts',
    'title' => 'Titel',
    'text' => 'Tekst',
    'image' => 'Afbeelding',
    'job_title' => 'Beroep',
    'image_caption' => 'Onderschrift bij afbeelding',
    'swap_text_image' => 'Omwisselen Tekst / Afbeelding',
    'confirm_copy_modal' => [
        'header' => 'Let op!',
        'message' => 'Weet je zeker dat je de structuur naar de andere taal wilt kopieren?',
        'confirm' => 'Ja, kopieer',
        'cancel' => 'Nee, annuleer',
    ],
    'copied' => 'Structuur gekopieerd!',
    'only_popular' => "Alleen de populaire"
];