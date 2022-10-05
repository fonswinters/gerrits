<?php
namespace Database\Seeders;

use Komma\KMS\Components\Component\ComponentSaveState;
use Komma\KMS\Components\ComponentArea\ComponentAreaSaveState;
use Komma\KMS\Components\ComponentArea\ComponentAreaService;
use App\Components\ComponentTypes;
use Illuminate\Database\Eloquent\Builder;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\ComponentArea;
use Komma\KMS\Globalization\Languages\Kms\LanguageService;
use Komma\KMS\Globalization\Languages\Models\Language;
use App\Pages\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Request;

class DefaultSitePageComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws ReflectionException
     * @throws Throwable
     */
    public function run()
    {
        /** @var ComponentAreaService $componentAreaService */
        $componentAreaService = app(ComponentAreaService::class);

        //Get the languages
        $languages = LanguageService::getAvailableLanguages();

        //Get the page component areas
        $componentAreaAttributesByIso2 = [];
        foreach($languages as $language) {
            $componentAreaAttributesByIso2[$language->iso_2] = $this->getComponentAreaForLanguage($languages->where('iso_2', $language->iso_2)->first());
        }

        //Create the components by delegating to specialized methods
        $this->createHomePageComponents(Page::where('code_name', '=', 'home')->first(), $componentAreaAttributesByIso2, $componentAreaService);
        $this->createAboutUsPageComponents(Page::where('code_name', '=', 'about')->first(), $componentAreaAttributesByIso2, $componentAreaService);
        $this->createReferencesPageComponents(Page::where('code_name', '=', 'references')->first(), $componentAreaAttributesByIso2, $componentAreaService);
        $this->createPrivacyPageComponents(Page::where('code_name', '=', 'privacy')->first(), $componentAreaAttributesByIso2, $componentAreaService);
        $this->createDisclaimerPageComponents(Page::where('code_name', '=', 'disclaimer')->first(), $componentAreaAttributesByIso2, $componentAreaService);
    }

    /**
     * @param Page $page
     * @param array $componentAreaAttributesByIso2
     * @param ComponentAreaService $componentAreaService
     * @throws ReflectionException
     * @throws Throwable
     */
    private function createHomePageComponents(Page $page, array $componentAreaAttributesByIso2, ComponentAreaService $componentAreaService)
    {
//        $contentButtons = Button::take(2)->get();
//        if($contentButtons->count() !== 2) throw new \RuntimeException('Not enough buttons! Seed more!');
//        $servicePoint = Servicepoint::first();
//        if(!$servicePoint) throw new \RuntimeException('I need a service point. Gimme one!');

        //Dutch
        //Create component save states
        $componentSaveStatesByIso2 = [];
        $componentSaveStatesByIso2['nl'] = [
            (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::CONTENT_PERSONAL)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|contentHeader|nl" => "Welkom!",
                "ComponentArea|dynamic_group_sections|C0|contentDescription|nl" => "<p>Algemene introductie van het bedrijf. Bacon ipsum dolor amet landjaeger kevin cow rump sirloin flank leberkas jowl shank short ribs prosciutto venison shankle. Sirloin bresaola pancetta, capicola porchetta sausage ball tip shoulder. Pork chop andouille turkey filet mignon. Fatback boudin prosciutto, hamburger swine jowl beef ribs chicken t-bone landjaeger frankfurter alcatra.&nbsp;</p>",
                "ComponentArea|dynamic_group_sections|C0|contentButtons|nl" => "1",
                "ComponentArea|dynamic_group_sections|C0|servicePointHeader|nl" => "Callout van maximaal 2 a 3 regels. Neem vrijblijvend contact op.",
                "ComponentArea|dynamic_group_sections|C0|servicepoints|nl" => "1",
                "ComponentArea|dynamic_group_sections|C0|servicePointButtons|nl" => "2"
            ])
            ->setSortOrder(0),

            (new ComponentSaveState())
            ->setId(-1)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::USP)
            ->setData([
                "ComponentArea|dynamic_group_sections|C-1|header|nl" => "5 redenen om <br>voor ons te kiezen",
                "ComponentArea|dynamic_group_sections|C-1|USP1|nl" => "Bacon ipsum dolor amet landjaeger kevin cow rump",
                "ComponentArea|dynamic_group_sections|C-1|USP2|nl" => "Sirloin flank leberkas jowl shank short ribs ",
                "ComponentArea|dynamic_group_sections|C-1|USP3|nl" => "Sirloin bresaola pancetta, capicola porchetta ",
                "ComponentArea|dynamic_group_sections|C-1|USP4|nl" => " Fatback boudin prosciutto, hamburger swine jowl",
                "ComponentArea|dynamic_group_sections|C-1|USP5|nl" => "Beef ribs chicken t-bone landjaeger frankfurter alcatra",
                "ComponentArea|dynamic_group_sections|C-1|USP6|nl" => "",
                "ComponentArea|dynamic_group_sections|C-1|USP7|nl" => "",
                "ComponentArea|dynamic_group_sections|C-1|buttons|nl" => "1",
                "ComponentArea|dynamic_group_sections|C-1|image|nl" => "[]"
            ])->setSortOrder(1)
        ];

        //English
        $componentSaveStatesByIso2['en'] = [(new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::CONTENT_PERSONAL)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|contentHeader|en" => "Welcome!",
                "ComponentArea|dynamic_group_sections|C0|contentDescription|en" => "<p>General company introduction here. Bacon ipsum dolor amet landjaeger kevin cow rump sirloin flank leberkas jowl shank short ribs prosciutto venison shankle. Sirloin bresaola pancetta, capicola porchetta sausage ball tip shoulder. Pork chop andouille turkey filet mignon. Fatback boudin prosciutto, hamburger swine jowl beef ribs chicken t-bone landjaeger frankfurter alcatra.&nbsp;</p>",
                "ComponentArea|dynamic_group_sections|C0|servicePointHeader|en" => "Callout van maximaal 2 a 3 regels. Neem vrijblijvend contact op.",
                "ComponentArea|dynamic_group_sections|C0|servicepoints|en" => "2",
                "ComponentArea|dynamic_group_sections|C0|contentButtons|en" => "6",
                "ComponentArea|dynamic_group_sections|C0|servicePointButtons|en" => "1"
            ])
            ->setSortOrder(0),

            (new ComponentSaveState())
            ->setId(-1)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::USP)
            ->setData([
                "ComponentArea|dynamic_group_sections|C-1|header|en" => "5 reasons to <br>choose for us",
                "ComponentArea|dynamic_group_sections|C-1|USP1|en" => "Bacon ipsum dolor amet landjaeger kevin cow rump",
                "ComponentArea|dynamic_group_sections|C-1|USP2|en" => "Sirloin flank leberkas jowl shank short ribs ",
                "ComponentArea|dynamic_group_sections|C-1|USP3|en" => "Sirloin bresaola pancetta, capicola porchetta ",
                "ComponentArea|dynamic_group_sections|C-1|USP4|en" => " Fatback boudin prosciutto, hamburger swine jowl",
                "ComponentArea|dynamic_group_sections|C-1|USP5|en" => "Beef ribs chicken t-bone landjaeger frankfurter alcatra",
                "ComponentArea|dynamic_group_sections|C-1|USP6|en" => "",
                "ComponentArea|dynamic_group_sections|C-1|USP7|en" => "",
                "ComponentArea|dynamic_group_sections|C-1|buttons|en" => "1",
                "ComponentArea|dynamic_group_sections|C-1|image|en" => "[]"
            ])->setSortOrder(1)
        ];


        //Fake some inputs. needed for the service
        Request::merge(['ComponentArea|dynamic_group_sections|C-1|image|nl' => '[]']); //Needed to fake a file input that the documentServices uses internally via the componentAreaService. Else it won't process the whole component area.
        Request::merge(['ComponentArea|dynamic_group_sections|C-1|image|de' => '[]']); //Needed to fake a file input that the documentServices uses internally via the componentAreaService. Else it won't process the whole component area.
        Request::merge(['ComponentArea|dynamic_group_sections|C-1|image|en' => '[]']); //Needed to fake a file input that the documentServices uses internally via the componentAreaService. Else it won't process the whole component area.

        //Create component area savestates ordered by by iso 2...
        foreach($componentSaveStatesByIso2 as $iso2 => $componentSaveStateByIso2) {
            $componentAreaSavestate = new ComponentAreaSaveState();
            foreach($componentSaveStatesByIso2[$iso2] as $componentSaveState) $componentAreaSavestate->addComponentSaveState($componentSaveState);

            $translation = $page->translations()->whereHas('language', function(Builder $query) use($iso2) {
                $query->where('iso_2', '=', $iso2);
            })->first();
            if(!$translation) continue;

            if(isset($componentAreaAttributesByIso2[$iso2])) {
                /** @var ComponentArea $componentAreaAttribute */
                $componentAreaAttribute = $componentAreaAttributesByIso2[$iso2];
                $componentAreaAttribute->setValue(json_encode($componentAreaSavestate));
                $componentAreaService->saveAttribute($translation, $componentAreaAttribute);
            }
        }
    }

    /**
     * @param Page $page
     * @param array $componentAreaAttributesByIso2
     * @param ComponentAreaService $componentAreaService
     * @throws ReflectionException
     * @throws Throwable
     */
    private function createAboutUsPageComponents(Page $page, array $componentAreaAttributesByIso2, ComponentAreaService $componentAreaService)
    {
        $componentSaveStates[] = (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::TEXT_IMAGE_BUTTON)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|text|nl" => "<h2>Passie met een grote P&nbsp;</h2>\n<p>Introductie van de persoon achter het bedrijf. Flank spare ribs pork chop landjaeger pig sirloin chuck, ground round biltong. Kevin tail beef ribs pork chop chicken venison frankfurter. Cupim beef ribs leberkas, ham tenderloin burgdoggen picanha landjaeger ball tip flank corned beef pastrami turducken t-bone shankle.&nbsp;</p>\n<p>Heb je nog vragen? Dr. Butcher helpt je graag. Neem vrijblijvend&nbsp;<a href=\"/contact\">contact</a> op.&nbsp;</p>",
                "ComponentArea|dynamic_group_sections|C0|buttons|nl" => "2",
                "ComponentArea|dynamic_group_sections|C0|image|nl" => "[]",
                "ComponentArea|dynamic_group_sections|C0|caption|nl" => "",
                "ComponentArea|dynamic_group_sections|C0|reversed|nl" => "0"
            ])->setSortOrder(0);


        //Create component area savestate, and put the component save states in ot
        $componentAreaSaveState = new ComponentAreaSaveState();
        foreach($componentSaveStates as $componentSaveState) $componentAreaSaveState->addComponentSaveState($componentSaveState);

        //Convert the savestate to json and give it to the component area attribute
        $saveStateJson = json_encode($componentAreaSaveState);
        $componentAreaAttributesByIso2['nl']->setValue($saveStateJson);

        $translation = $page->translations()->whereHas('language', function(Builder $query) {
            $query->where('iso_2', '=', 'nl');
        })->first();
        if(!$translation) return;

        Request::merge(['ComponentArea|dynamic_group_sections|C0|image|nl' => '[]']); //Needed to fake a file input that the documentServices uses internally via the componentAreaService. Else it won't process the whole component area.

        //Let the componentAreaService save it
        $componentAreaService->saveAttribute($translation, $componentAreaAttributesByIso2['nl']);
    }


    /**
     * @param Page $page
     * @param array $componentAreaAttributesByIso2
     * @param ComponentAreaService $componentAreaService
     * @throws ReflectionException
     * @throws Throwable
     */
    private function createReferencesPageComponents(Page $page, array $componentAreaAttributesByIso2, ComponentAreaService $componentAreaService)
    {
        $componentSaveStates[] = (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::TEXT_IMAGE_BUTTON)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|text|nl" => "<h2>Mooie verhalen van tevreden klanten.</h2>\n<p>Eigen lof stinkt. En als liefhebbers van heerlijke aroma’s zullen we dus niet opscheppen. Lees wat onze klanten zeggen!</p> <p>Cupim beef ribs leberkas, ham tenderloin burgdoggen picanha landjaeger ball tip flank corned beef pastrami turducken t-bone shankle.&nbsp;</p>\n",
                "ComponentArea|dynamic_group_sections|C0|buttons|nl" => "2",
                "ComponentArea|dynamic_group_sections|C0|image|nl" => "[]",
                "ComponentArea|dynamic_group_sections|C0|caption|nl" => "",
                "ComponentArea|dynamic_group_sections|C0|reversed|nl" => "1"
            ])->setSortOrder(0);


        //Create component area savestate, and put the component save states in ot
        $componentAreaSaveState = new ComponentAreaSaveState();
        foreach($componentSaveStates as $componentSaveState) $componentAreaSaveState->addComponentSaveState($componentSaveState);

        //Convert the savestate to json and give it to the component area attribute
        $saveStateJson = json_encode($componentAreaSaveState);
        $componentAreaAttributesByIso2['nl']->setValue($saveStateJson);

        $translation = $page->translations()->whereHas('language', function(Builder $query) {
            $query->where('iso_2', '=', 'nl');
        })->first();
        if(!$translation) return;

        Request::merge(['ComponentArea|dynamic_group_sections|C0|image|nl' => '[]']); //Needed to fake a file input that the documentServices uses internally via the componentAreaService. Else it won't process the whole component area.

        //Let the componentAreaService save it
        $componentAreaService->saveAttribute($translation, $componentAreaAttributesByIso2['nl']);
    }

    /**
     * @param Page $page
     * @param array $componentAreaAttributesByIso2
     * @param ComponentAreaService $componentAreaService
     * @throws ReflectionException
     * @throws Throwable
     */
    private function createPrivacyPageComponents(Page $page, array $componentAreaAttributesByIso2, ComponentAreaService $componentAreaService)
    {
        $componentSaveStatesByIso2[] = [];
        $componentSaveStatesByIso2['nl'] = [(new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::TEXT)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|text|nl" => "<h2>Cookies</h2>
                <p>Op onze website maken we gebruik van verschillende cookies. Cookies zijn kleine stukjes informatie die gedurende een bezoek aan een website worden opgeslagen op je computer. Sommige cookies zijn nodig voor het juist functioneren van een website, waar andere cookies persoonlijke informatie verzamelen. Cookies van <span style=\"text-decoration: line-through;\"><strong>komma.nl</strong></span> zijn veilig voor je pc, laptop, telefoon of tablet.</p>
                <h2>Functionele cookies</h2>
                <p>Wij gebruiken cookies om ervoor te zorgen dat je onze website op een prettige manier kunt gebruiken en snel is.</p>
                <h2>Analytische cookies</h2>
                <p>Door middel van deze cookies verkrijgen we inzicht in het gebruik van onze website. We maken hiervoor gebruik van cookies ten behoeve van Google Analytics en Google Tag Manager. De privacy van bezoekers blijft met deze analytische cookies gewaarborgd. Met behulp van de verkregen data kan de website worden getoetst. Zo kunnen we onder andere fouten en ongemakken opsporen, verbeteringen testen en de gebruikerservaring optimaliseren.</p>
                <h2>Tracking cookies</h2>
                <p>Deze cookies volgen bezoekers wanneer ze verschillende websites bezoeken. Het doel hiervan is om bezoekers relevante advertenties te tonen op basis van de verzamelde data. <span style=\"text-decoration: line-through;\"><strong>Komma</strong></span> deelt verzamelde persoonsgegevens met behulp van tracking cookies met Bing Ads, zodat gebruik kan worden gemaakt van aangeboden advertentiediensten.</p>
                <h2>Cookies verwijderen of uitschakelen</h2>
                <p>De functionele cookies van <strong><span style=\"text-decoration: line-through;\">komma.nl</span></strong> verdwijnen automatisch als je je browser sluit. Andere cookies blijven langer staan, maar kun je via de browserinstellingen verwijderen of uitschakelen. Maar let wel op: het kan zijn dat de website dan minder goed te gebruiken is.</p>"
            ])->setSortOrder(0)
        ];

        $componentSaveStatesByIso2['en'] = [(new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::TEXT)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|text|en" => "<h2>Cookies</h2>
                    <p>We use various cookies on our website. Cookies are small pieces of information that are stored on your computer during a visit to a website. Some cookies are necessary for the proper functioning of a website, whereas other cookies collect personal information. Cookies from <strong><span style=\"text-decoration: line-through;\">komma.nl</span></strong> are safe for your PC, laptop, phone or tablet.</p>
                    <h2>Functional cookies</h2>
                    <p>We use cookies to ensure a pleasant user-experience and a fast operation of our website.</p>
                    <h2>Analytical cookies</h2>
                    <p>These cookies provide us with an insight into the use of our website. To this end, we use cookies for Google Analytics and Google Tag Manager. The use of analytical cookies does not affect the privacy of visitors. The website can be tested based on the data that has been obtained. This, for example, enables us to detect errors and inconveniences, test improvements and optimise user experience.</p>
                    <h2>Tracking cookies</h2>
                    <p>These cookies track visitors when they visit different websites. The purpose of this is to show visitors relevant advertisements based on the collected data. <strong><span style=\"text-decoration: line-through;\">Komma</span></strong> shares personal data that has been collected using tracking cookies with Bing Ads, with the aim of using their advertising services.</p>
                    <h2>Deleting or disabling cookies</h2>
                    <p>The functional cookies of <span style=\"text-decoration: line-through;\"><strong>komma.nl</strong></span> are automatically removed when you close your browser. Other cookies remain longer but can be removed or disabled via the browser settings. Note, however, that doing so may impair the functionality of the website.</p>"
            ])->setSortOrder(0)
        ];


        $translation = $page->translations()->whereHas('language', function(Builder $query) {
            $query->where('iso_2', '=', 'nl');
        })->first();

        //Create component area savestates ordered by by iso 2...
        foreach($componentSaveStatesByIso2 as $iso2 => $componentSaveStateByIso2) {
            $componentAreaSavestate = new ComponentAreaSaveState();
            foreach($componentSaveStatesByIso2[$iso2] as $componentSaveState) $componentAreaSavestate->addComponentSaveState($componentSaveState);

            if(isset($componentAreaAttributesByIso2[$iso2])) {
                /** @var ComponentArea $componentAreaAttribute */
                echo 'Saving privacy page for iso '.$iso2.PHP_EOL;
                $componentAreaAttribute = $componentAreaAttributesByIso2[$iso2];
                $componentAreaAttribute->setValue(json_encode($componentAreaSavestate));
                $componentAreaService->saveAttribute($translation, $componentAreaAttribute);
            }
        }
    }

    /**
     * @param Page $page
     * @param array $componentAreaAttributesByIso2
     * @param ComponentAreaService $componentAreaService
     * @throws ReflectionException
     * @throws Throwable
     */
    private function createDisclaimerPageComponents(Page $page, array $componentAreaAttributesByIso2, ComponentAreaService $componentAreaService)
    {
        $componentSaveStatesByIso2[] = [];
        $componentSaveStatesByIso2['nl'] = [(new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::TEXT)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|text|nl" => "<h2>Disclaimer</h2>
                <h3>Algemeen</h3>
                <p>De bepalingen uit deze disclaimer zijn van toepassing op de website die is gekoppeld aan het internetadres <span style=\"text-decoration: line-through;\"><strong>www.komma.nl</strong></span>. Mocht u problemen ondervinden op onze website, laat ons dit dan weten door het sturen van een email. Wij zullen dan onze uiterste beste doen het probleem zo spoedig mogelijk te verhelpen.</p>
                <h3>Elektronische communicatie</h3>
                <p>U kunt ons, nu en in de toekomst, op verschillende manieren bereiken. Onder meer via de website en e-mail. Deze vormen van elektronische communicatie zijn nooit 100% veilig. Door gevoelige of vertrouwelijke informatie zonder &lsquo;versleuteling&rsquo; via het open internet aan ons te versturen aanvaardt u het risico, dat deze informatie bij derden terechtkomt.</p>
                <h3>Informatie; hyperlinks</h3>
                <p>Wij bieden, tenzij uitdrukkelijk anders wordt vermeld, de informatie op de website aan als een vorm van dienstverlening. In het bijzonder aan onze huidige en toekomstige relaties, maar in feite aan een ieder die toegang heeft tot internet. Er kunnen geen rechten aan worden ontleend. Wij betrachten altijd de grootst mogelijke zorgvuldigheid bij het samenstellen en/of opmaken van onze websites. Dit betekent evenwel niet, dat wij kunnen garanderen dat de op de website verstrekte informatie altijd juist, up-to-date of compleet is. In de gevallen, dat op de website hyperlinks worden aangeboden betekent dit niet, dat wij de via die links eventueel aangeboden diensten of producten aanbevelen. Ook voor de juistheid van de informatie op die websites kunnen wij niet instaan.</p>
                <h3>Aansprakelijkheid</h3>
                <p><span style=\"text-decoration: line-through;\"><strong>Komma</strong></span> en/of onze werknemers zijn niet aansprakelijk voor enigerlei schade die ontstaat als gevolg van (onzorgvuldig of onjuist) gebruik van de informatie of mogelijkheden die de website biedt of gebruik van de website zelf, tenzij er sprake is van opzet of bewuste roekeloosheid. Gebruik van de website geschiedt dan ook volledig voor uw eigen risico. Ook zijn wij niet aansprakelijk in de gevallen dat de website (tijdelijk) niet bereikbaar is.</p>
                <h3>Wijzigingen</h3>
                <p>Wij zullen de informatie op de website periodiek aanvullen en eventueel wijzigen. Dit kunnen wij steeds onmiddellijk en zonder enige kennisgeving vooraf doen.</p>
                <h3>Rechten van intellectuele eigendom</h3>
                <p>Ten aanzien van alle informatie en/of andere auteursrechtelijk beschermde werken op de websites behouden wij uitdrukkelijk onze auteursrechten en die van onze licentiegevers voor. Voor gebruik van het hiervoor bedoelde heeft u voorafgaande toestemming van ons nodig. U kunt uw verzoeken in dit verband aan ons richten per e-mail.</p>
                <h3>Toepasselijk recht</h3>
                <p>Op de websites en deze disclaimer is Nederlands recht van toepassing. Alle eventuele uit hoofde van de websites en de disclaimer voortvloeiende geschillen zullen bij uitsluiting worden voorgelegd aan de bevoegde rechter in Nederland.</p>"
            ])->setSortOrder(0)
        ];

        $componentSaveStatesByIso2['en'] = [(new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::TEXT)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|text|en" => "<h2>Disclaimer</h2>
                    <h3>General</h3>
                    <p>The conditions in this disclaimer apply to the website linked to the internet address <span style=\"text-decoration: line-through;\"><strong>www.komma.nl</strong></span>. If you should experience any problems with our website, please inform us by sending us an email. We will then do our best to resolve the issue as soon as possible.</p>
                    <h3>Online contact</h3>
                    <p>You can contact us in a variety of ways, including by email and via our website. These forms of electronic communication are never 100% secure. By sending us confidential information via the internet without encryption, you accept the risk that this information might fall into the hands of third parties.</p>
                    <h3>Information, hyperlinks</h3>
                    <p>Unless explicitly mentioned otherwise, we provide the information on our website as a form of service, in particular for our current and future contacts, but in effect for everyone who has access to the internet. No rights can be derived from this. We always exercise the utmost care in constructing and/or designing our websites. However, this does not mean that we can guarantee that the information published on the website is always correct and up-to-date. When we post hyperlinks on our website, this does not imply that we recommend any services or products offered via those links. Furthermore, we cannot ensure the accuracy of these websites.</p>
                    <h3>Liability</h3>
                    <p><span style=\"text-decoration: line-through;\"><strong>Komma</strong></span> and/or our employees are in no way liable for damage resulting from careless or incorrect use of the information or options offered by the website, or caused by use of the website itself, unless this is the result of wilful intent or deliberate recklessness. Use of this website is therefore entirely at your own risk. We also accept no liability if the website is unavailable.</p>
                    <h3>Amendments</h3>
                    <p>We will periodically add to, or if necessary, amend the information on the website. We can do this immediately and without any prior notice.</p>
                    <h3>Intellectual property rights</h3>
                    <p>We explicitly reserve all our copyrights and those of our licensers in respect to all information and/or other copyrighted works on the website we explicitly reserve all our copyrights and those of our licensers. You must obtain our consent in advance before using the aforementioned items. All requests related to this can be forwarded to us by email.</p>
                    <h3>Applicable law</h3>
                    <p>The law of the Netherlands applies to the websites and to this disclaimer. Any disputes arising from the websites and the disclaimer shall be exclusively settled by a competent court in the Netherlands.</p>"
            ])->setSortOrder(0)
        ];

        //Create component area savestates ordered by by iso 2...
        foreach($componentSaveStatesByIso2 as $iso2 => $componentSaveStateByIso2) {
            $componentAreaSavestate = new ComponentAreaSaveState();
            foreach($componentSaveStatesByIso2[$iso2] as $componentSaveState) $componentAreaSavestate->addComponentSaveState($componentSaveState);

            $translation = $page->translations()->whereHas('language', function(Builder $query) use($iso2) {
                $query->where('iso_2', '=', $iso2);
            })->first();
            if(!$translation) break;

            if(isset($componentAreaAttributesByIso2[$iso2])) {
                /** @var ComponentArea $componentAreaAttribute */
                $componentAreaAttribute = $componentAreaAttributesByIso2[$iso2];
                $componentAreaAttribute->setValue(json_encode($componentAreaSavestate));
                $componentAreaService->saveAttribute($translation, $componentAreaAttribute);
            }
        }
    }

    /**
     * @param Language $language
     * @return Attribute|ComponentArea
     */
    private function getComponentAreaForLanguage(Language $language)
    {
        return (new ComponentArea())
            ->setComponentTypes([
                ComponentTypes::TEXT,
                ComponentTypes::IMAGE,
                ComponentTypes::TEXT_IMAGE_BUTTON,
                ComponentTypes::VIDEO,
                ComponentTypes::USP,
                ComponentTypes::QUOTE,
                ComponentTypes::CONTENT_PERSONAL,
                ComponentTypes::CONTENT_SLIDER,
            ])
            ->setSubFolder('dynamic_group_sections')
            ->mapValueFrom(Attribute::ValueFromComponentArea, 'dynamic_group_sections')
            ->setAssociatedLanguage($language);
    }
}
