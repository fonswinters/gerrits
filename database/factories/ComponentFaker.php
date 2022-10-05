<?php
namespace Database\Factories;

use App\Buttons\Models\Button;
use App\Components\ComponentTypes;
use Komma\KMS\Components\Component\Component;
use Komma\KMS\Components\Component\ComponentSaveState;
use Komma\KMS\Components\ComponentArea\ComponentAreaService;
use App\Servicepoints\Models\Servicepoint;
use Illuminate\Support\Str;
use Faker\Provider\Base;


class ComponentFaker extends Base
{
    /**
     * @return ComponentSaveState
     */
    private static function contentPersonalSaveState() {
        /** @var Servicepoint $servicePoint */
        $servicePoint = Servicepoint::inRandomOrder()->first();
        $button = Button::first();

        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::CONTENT_PERSONAL)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|contentHeader|nl" => "Inspireren om te groeien",
                "ComponentArea|dynamic_group_sections|C0|contentDescription|nl" => "<p>Van kraakhelder concept tot krachtige website: Komma realiseert totaaloplossingen voor ondernemers met groeiplannen. Wij zijn een pain-in-the-ass bij brainstormsessies, maar overdonderen je ook met nieuwe ideeën om het maximale uit je bedrijf te halen. Met onze digitale roots en een gezonde portie boerenverstand blijven we je verrassen. Nu én in de toekomst, want we zijn niet van de eenmalige avontuurtjes. Een komma is geen punt.</p>",
                "ComponentArea|dynamic_group_sections|C0|servicePointHeader|nl" => "Benieuwd? Neem contact op!",
                "ComponentArea|dynamic_group_sections|C0|servicepoints|nl" => "".($servicePoint->id ?? "")."",
                "ComponentArea|dynamic_group_sections|C0|servicePointButtons|nl" => "".($button->id ?? "").""
            ]);
    }

    /**
     * @return ComponentSaveState
     */
    private static function textSaveState() {
        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::TEXT)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|text|nl" => "<h2>Dit is een tekst voor het tekst component</h2><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, aspernatur blanditiis consequuntur delectus deserunt, dolorem dolores ea enim eos et iure maxime numquam odit omnis quis quo, rerum sapiente sequi tempore voluptatibus. Aliquam consequatur cum dolore eum, exercitationem possimus provident quam quod sint soluta temporibus tenetur, veritatis. Accusamus aliquid atque eius eum molestias mollitia possimus reprehenderit! Consectetur doloremque odio quod?</p>"
            ]);
    }

    /**
     * @return ComponentSaveState
     */
    private static function doubleTextSaveState() {
        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::DOUBLE_TEXT)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|left|nl" => "<h2>Dit is de tekst voor de linkerkant.</h2><p>Asperiores est ex molestias. Accusamus architecto autem cupiditate doloremque eius itaque libero magni odio odit repellendus soluta, voluptas, voluptates voluptatum? Numquam, vero!</p>",
                "ComponentArea|dynamic_group_sections|C0|right|nl" => "<h2>Dit is de tekst voor de rechterkant.</h2><p>Culpa dolorem hic ipsam magni, sapiente sunt voluptate voluptatibus. Accusamus aspernatur autem culpa doloremque dolorum laboriosam, perferendis placeat quis unde? Consectetur, corporis culpa dolore ex expedita hic ipsum itaque magnam modi molestias natus nulla officia possimus quas quia quod unde veniam veritatis?</p>"
            ]);
    }

    /**
     * @return ComponentSaveState
     */
    private static function imageSaveState() {
        //Warning the image document does not exist. It is fake
        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::IMAGE)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|image|nl" => "[{\"id\":3,\"file_url\":\"/fake/image.jpg\",\"state\":\"modified\",\"name\":\"attachment kit\",\"sort_order\":1,\"thumb_image_url\":\"https://picsum.photos/500/350\",\"small_image_url\":\"https://picsum.photos/500/350\",\"medium_image_url\":\"https://picsum.photos/500/350\",\"large_image_url\":\"\",\"documentable_id\":3,\"documentable_type\":\"\",\"created_at\":\"2020-01-16 13:22:26\",\"updated_at\":\"2020-01-16 13:22:26\"}]",
                "ComponentArea|dynamic_group_sections|C0|caption|nl" => "Onderschrift voor de afbeelding"
            ]);
    }

    /**
     * @return ComponentSaveState
     */
    private static function doubleImageSaveState() {
        //Warning the image document does not exist. It is fake
        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::DOUBLE_IMAGE)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|image_one|nl" => "[{\"id\":3,\"file_url\":\"/fake/image.jpg\",\"state\":\"modified\",\"name\":\"attachment kit\",\"sort_order\":1,\"thumb_image_url\":\"https://picsum.photos/500/350\",\"small_image_url\":\"https://picsum.photos/500/350\",\"medium_image_url\":\"https://picsum.photos/500/350\",\"large_image_url\":\"\",\"documentable_id\":3,\"documentable_type\":\"\",\"created_at\":\"2020-01-16 13:22:26\",\"updated_at\":\"2020-01-16 13:22:26\"}]",
                "ComponentArea|dynamic_group_sections|C0|image_two|nl" => "[{\"id\":-1,\"file_url\":\"/fake/image.jpg\",\"state\":\"modified\",\"name\":\"fake document\",\"sort_order\":1,\"thumb_image_url\":\"https://picsum.photos/500/350\",\"small_image_url\":\"https://picsum.photos/500/350\",\"medium_image_url\":\"https://picsum.photos/500/350\",\"large_image_url\":\"\",\"documentable_id\":4,\"documentable_type\":\"\",\"created_at\":\"2020-01-16 13:22:30\",\"updated_at\":\"2020-01-16 13:22:30\"}]",
                "ComponentArea|dynamic_group_sections|C0|reversed|nl" => "0"
            ]);
    }

    /**
     * @return ComponentSaveState
     */
    private static function videoSaveState() {
        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::VIDEO)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|video|nl_video_id" => "uBdfduMtaz0",
                "ComponentArea|dynamic_group_sections|C0|video|nl_autoplay" => "on",
                "ComponentArea|dynamic_group_sections|C0|video|nl" => "0,uBdfduMtaz0"
            ]);
    }

    /**
     * @return ComponentSaveState
     */
    private static function uspSaveState() {
        $button = Button::first();

        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::USP)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|header|nl" => "Dit is een lijst met een afbeelding en eventueel een knop",
                  "ComponentArea|dynamic_group_sections|C0|USP1|nl" => "Regel 1 item",
                  "ComponentArea|dynamic_group_sections|C0|USP2|nl" => "Regel 2item",
                  "ComponentArea|dynamic_group_sections|C0|USP3|nl" => "Regel 3 item",
                  "ComponentArea|dynamic_group_sections|C0|USP4|nl" => "Regel 4 item",
                  "ComponentArea|dynamic_group_sections|C0|USP5|nl" => "Regel 5 item",
                  "ComponentArea|dynamic_group_sections|C0|USP6|nl" => "Regel 6 item",
                  "ComponentArea|dynamic_group_sections|C0|USP7|nl" => "Regel 7 item",
                  "ComponentArea|dynamic_group_sections|C0|buttons|nl" => "".$button->id ?? "",
                  "ComponentArea|dynamic_group_sections|C0|image|nl" => "[{\"id\":1,\"file_url\":\"/img/placeholder-usp.svg\",\"state\":\"pristine\",\"name\":\"black-vinyl-record-playing-on-turntable-1389429\",\"sort_order\":1,\"thumb_image_url\":\"/img/placeholder-usp.svg\",\"small_image_url\":\"\",\"medium_image_url\":\"\",\"large_image_url\":\"/img/placeholder-usp.svg\",\"documentable_id\":1,\"documentable_type\":\"App\\\\Components\\\\Component\\\\Component\",\"created_at\":\"2020-01-31 16:05:46\",\"updated_at\":\"2020-01-31 16:05:48\"}]"
            ]);
    }

    /**
     * @return ComponentSaveState
     */
    private static function quoteSaveState() {
        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::QUOTE)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|text|nl" => "Ik kwam, ik zag, en ik vergat wat ik van plan was",
                "ComponentArea|dynamic_group_sections|C0|title|nl" => "Vergetelheid",
                "ComponentArea|dynamic_group_sections|C0|subtitle|nl" => "Geheugenkampioen",
                "ComponentArea|dynamic_group_sections|C0|image|nl" => "[{\"id\":1,\"file_url\":\"/img/placeholder-person.svg\",\"state\":\"pristine\",\"name\":\"black-vinyl-record-playing-on-turntable-1389429\",\"sort_order\":1,\"thumb_image_url\":\"/img/placeholder-person.svg\",\"small_image_url\":\"\",\"medium_image_url\":\"\",\"large_image_url\":\"/img/placeholder-person.svg\",\"documentable_id\":1,\"documentable_type\":\"App\\\\Components\\\\Component\\\\Component\",\"created_at\":\"2020-01-31 16:05:46\",\"updated_at\":\"2020-01-31 16:05:48\"}]",
            ]);
    }

    /**
     * @return ComponentSaveState
     */
    private static function contentSliderSaveState() {
        $button = Button::first();
        $button2 = Button::skip(1)->take(1)->first() ?? $button;

        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::CONTENT_SLIDER)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|label_1|nl" => "Tabblad 1",
                "ComponentArea|dynamic_group_sections|C0|header_1|nl" => "Tabblad 1 titel",
                "ComponentArea|dynamic_group_sections|C0|text_1|nl" => "<p>Tabblad 1 tekst</p>",
                "ComponentArea|dynamic_group_sections|C0|buttons_1|nl" => "".($button->id ?? ""),
                "ComponentArea|dynamic_group_sections|C0|image_1|nl" => "[{\"id\":-1,\"file_url\":\"/fake/image.jpg\",\"state\":\"modified\",\"name\":\"fake document\",\"sort_order\":1,\"thumb_image_url\":\"https://picsum.photos/500/350\",\"small_image_url\":\"https://picsum.photos/500/350\",\"medium_image_url\":\"https://picsum.photos/500/350\",\"large_image_url\":\"\",\"documentable_id\":4,\"documentable_type\":\"\",\"created_at\":\"2020-01-16 13:22:30\",\"updated_at\":\"2020-01-16 13:22:30\"}]",
                "ComponentArea|dynamic_group_sections|C0|reversed_1|nl" => "0",
                "ComponentArea|dynamic_group_sections|C0|label_2|nl" => "Tabblad 2",
                "ComponentArea|dynamic_group_sections|C0|header_2|nl" => "Tabblad 2 titel",
                "ComponentArea|dynamic_group_sections|C0|text_2|nl" => "<p>Tabblad 2 tekst</p>",
                "ComponentArea|dynamic_group_sections|C0|buttons_2|nl" => "",
                "ComponentArea|dynamic_group_sections|C0|image_2|nl" => "[]",
                "ComponentArea|dynamic_group_sections|C0|reversed_2|nl" => "0",
                "ComponentArea|dynamic_group_sections|C0|label_3|nl" => "Tabblad 3",
                "ComponentArea|dynamic_group_sections|C0|header_3|nl" => "Tabblad 3 titel",
                "ComponentArea|dynamic_group_sections|C0|text_3|nl" => "<p>Tabblad 3 tekst</p>",
                "ComponentArea|dynamic_group_sections|C0|buttons_3|nl" => "",
                "ComponentArea|dynamic_group_sections|C0|image_3|nl" => "[]",
                "ComponentArea|dynamic_group_sections|C0|reversed_3|nl" => "0",
                "ComponentArea|dynamic_group_sections|C0|label_4|nl" => "Tabblad 4",
                "ComponentArea|dynamic_group_sections|C0|header_4|nl" => "Tabblad 4 titel",
                "ComponentArea|dynamic_group_sections|C0|text_4|nl" => "<p>Tabblad 4 tekst</p>",
                "ComponentArea|dynamic_group_sections|C0|buttons_4|nl" => "".($button2->id ?? ""),
                "ComponentArea|dynamic_group_sections|C0|image_4|nl" => "[{\"id\":-1,\"file_url\":\"/fake/image.jpg\",\"state\":\"modified\",\"name\":\"fake document\",\"sort_order\":1,\"thumb_image_url\":\"https://picsum.photos/500/350\",\"small_image_url\":\"https://picsum.photos/500/350\",\"medium_image_url\":\"https://picsum.photos/500/350\",\"large_image_url\":\"\",\"documentable_id\":4,\"documentable_type\":\"\",\"created_at\":\"2020-01-16 13:22:30\",\"updated_at\":\"2020-01-16 13:22:30\"}]",
                "ComponentArea|dynamic_group_sections|C0|reversed_4|nl" => "0"
            ]);
    }

    /**
     * @return ComponentSaveState
     */
    private static function downloadsSaveState() {
        /** @var Servicepoint $servicePoint */
        $servicePoint = Servicepoint::inRandomOrder()->first();

        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::DOWNLOADS)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|download_title|nl" => "Onze downloads",
                "ComponentArea|dynamic_group_sections|C0|downloads|nl" => "[
                    {\"id\":11,\"file_url\":\"/uploads/.gitignore\",\"state\":\"modified\",\"name\":\"git ignore\",\"sort_order\":1,\"thumb_image_url\":\"\",\"small_image_url\":\"\",\"medium_image_url\":\"\",\"large_image_url\":\"\",\"documentable_id\":11,\"documentable_type\":\"\",\"created_at\":\"2020-01-16 14:04:01\",\"updated_at\":\"2020-01-16 14:04:01\"},
                    {\"id\":11,\"file_url\":\"/uploads/.gitignore\",\"state\":\"modified\",\"name\":\"git test ignore\",\"sort_order\":1,\"thumb_image_url\":\"\",\"small_image_url\":\"\",\"medium_image_url\":\"\",\"large_image_url\":\"\",\"documentable_id\":11,\"documentable_type\":\"\",\"created_at\":\"2020-01-16 14:04:01\",\"updated_at\":\"2020-01-16 14:04:01\"},
                    {\"id\":11,\"file_url\":\"/uploads/.gitignore\",\"state\":\"modified\",\"name\":\"git test ignore 2\",\"sort_order\":1,\"thumb_image_url\":\"\",\"small_image_url\":\"\",\"medium_image_url\":\"\",\"large_image_url\":\"\",\"documentable_id\":11,\"documentable_type\":\"\",\"created_at\":\"2020-01-16 14:04:01\",\"updated_at\":\"2020-01-16 14:04:01\"}
                ]",
                "ComponentArea|dynamic_group_sections|C0|personal_header|nl" => "Contact persoon titel",
                "ComponentArea|dynamic_group_sections|C0|servicepoints|nl" => "".$servicePoint->id,
                "ComponentArea|dynamic_group_sections|C0|reversed|nl" => "0"
            ]);
    }

    /**
     * @return ComponentSaveState
     */
    private static function textImageSaveState() {

        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::TEXT_IMAGE)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|text|nl" => "<p>Schrijven is een van de manieren waarop taal kan worden gebruikt. Taalgebruik is namelijk gebaseerd op het vermogen geschreven of gesproken taal te begrijpen of te produceren. Bij schrijven gaat het om het produceren van geschreven woorden en zinnen.<br />Hierbij wordt een woord uit het mentale lexicon (een soort mentaal woordenboek) opgediept, en vervolgens via een aantal tussenstappen (analyse van respectievelijk de woordbetekenis, de visuele woordvorm, opstellen van een schrijfplan) in een geschreven woord omgezet. Mogelijk wordt, net als bij het lezen, bij het schrijven soms gebruikgemaakt van een indirecte of fonologische route, dat wil zeggen dat het woord eerst innerlijk wordt uitgesproken en daarna wordt vertaald naar een orthografische code (de visuele woordvorm of het schriftbeeld).</p>",
                "ComponentArea|dynamic_group_sections|C0|image|nl" => "[{\"id\":-1,\"file_url\":\"/fake/image.jpg\",\"state\":\"modified\",\"name\":\"fake document\",\"sort_order\":1,\"thumb_image_url\":\"https://picsum.photos/500/350\",\"small_image_url\":\"https://picsum.photos/500/350\",\"medium_image_url\":\"https://picsum.photos/500/350\",\"large_image_url\":\"\",\"documentable_id\":4,\"documentable_type\":\"\",\"created_at\":\"2020-01-16 13:22:30\",\"updated_at\":\"2020-01-16 13:22:30\"}]",
                "ComponentArea|dynamic_group_sections|C0|caption|nl" => "Onderschrift voor de afbeelding",
                "ComponentArea|dynamic_group_sections|C0|reversed|nl" => "1"
            ]);
    }


    /**
     * @return ComponentSaveState
     */
    private static function textImageButtonSaveState() {
        $button = Button::first();

        return (new ComponentSaveState())
            ->setId(0)
            ->setVersion(ComponentAreaService::SAVE_VERSION)
            ->setComponentTypeId(ComponentTypes::TEXT_IMAGE_BUTTON)
            ->setData([
                "ComponentArea|dynamic_group_sections|C0|text|nl" => "<p>Schrijven is een van de manieren waarop taal kan worden gebruikt. Taalgebruik is namelijk gebaseerd op het vermogen geschreven of gesproken taal te begrijpen of te produceren. Bij schrijven gaat het om het produceren van geschreven woorden en zinnen.<br />Hierbij wordt een woord uit het mentale lexicon (een soort mentaal woordenboek) opgediept, en vervolgens via een aantal tussenstappen (analyse van respectievelijk de woordbetekenis, de visuele woordvorm, opstellen van een schrijfplan) in een geschreven woord omgezet. Mogelijk wordt, net als bij het lezen, bij het schrijven soms gebruikgemaakt van een indirecte of fonologische route, dat wil zeggen dat het woord eerst innerlijk wordt uitgesproken en daarna wordt vertaald naar een orthografische code (de visuele woordvorm of het schriftbeeld).</p>",
                "ComponentArea|dynamic_group_sections|C0|buttons|nl" => "".$button->id ?? "",
                "ComponentArea|dynamic_group_sections|C0|image|nl" => "[{\"id\":-1,\"file_url\":\"/fake/image.jpg\",\"state\":\"modified\",\"name\":\"fake document\",\"sort_order\":1,\"thumb_image_url\":\"https://picsum.photos/500/350\",\"small_image_url\":\"https://picsum.photos/500/350\",\"medium_image_url\":\"https://picsum.photos/500/350\",\"large_image_url\":\"\",\"documentable_id\":4,\"documentable_type\":\"\",\"created_at\":\"2020-01-16 13:22:30\",\"updated_at\":\"2020-01-16 13:22:30\"}]",
                "ComponentArea|dynamic_group_sections|C0|caption|nl" => "Onderschrift voor de afbeelding",
                "ComponentArea|dynamic_group_sections|C0|reversed|nl" => "1"
            ]);
    }



    /**
     * Get a component sample
     *
     * @param int $componentType
     * @return Component
     */
    public static function componentOfType(int $componentType): Component
    {
        //Get sample save state for a certain component type
        if(!in_array($componentType, ComponentTypes::getAsArray())) throw new InvalidArgumentException('The given component type number was not a value of the '.ComponentTypes::class.'');
        $componentTypeEnumName = array_search($componentType, ComponentTypes::getAsArray(), true);
        $methodName = Str::camel(strtolower($componentTypeEnumName)).'SaveState';
        if(!method_exists(self::class, $methodName)) throw new RuntimeException('There must be a method called '.$methodName.'. But it did not exist.');
        /** @var ComponentSaveState $saveState */
        $saveState = call_user_func([self::class, $methodName]);

        //Create a component and return it.
        $component = new Komma\KMS\Components\Component\Component([
            'component_area_id' => 0,
            'component_type_id' => $saveState->getComponentTypeId(),
            'sort_order' => 0,
            'version' => $saveState->getVersion(),
            'data' => json_encode($saveState->getData()),
        ]);

        return $component;
    }
}