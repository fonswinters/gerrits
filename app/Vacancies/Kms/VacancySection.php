<?php


namespace App\Vacancies\Kms;

//The new object oriented attributes
use App\Buttons\Kms\ButtonService;
use App\Components\ComponentTypes;
use Illuminate\Database\Eloquent\Model;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\ComponentArea;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextArea;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;
use Komma\KMS\Core\Sections\Section;
use App\Servicepoints\Kms\ServicepointService;
use App\Servicepoints\Models\Servicepoint;
use Komma\KMS\Users\Models\KmsUserRole;

final class VacancySection extends Section
{
    public function defineAttributesAndTabs(Model $currentModel = null): void
    {
        $servicePointService = \App::make(ServicepointService::class);
        $servicePointOptions = $servicePointService->getOptionsForSelect(false, true);

        $buttonsService = \App::make(ButtonService::class);
        $buttonModels = $buttonsService->getOptionsForSelect();

        $this->tabs->makeTab()->addItems([
            (new OnOff())
                ->setLabelText(__('KMS::global.active'))
                ->switchOn()
                ->setReference( 'active'),

//            (new Seperator())->setMinimumUserRole(KmsUserRole::SuperAdmin),

//            (new Title())
//                ->setLabelText(__('KMS::global.servicePoint'))
//                ->setMinimumUserRole(KmsUserRole::SuperAdmin),

//            (new TextField())
//                ->setLabelText('Titel')
//                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
//                ->setReference( 'servicepoint_heading'),

            (new MultiSelect())
                ->setItems($servicePointOptions->toArray())
                ->setLabelText(__('KMS::servicePoints.entity'))
                ->setMaxItemsToSelect(1)
                ->canBeLinkedWith(Servicepoint::class)
                ->setMinimumUserRole(KmsUserRole::Admin)
                ->setReference('servicepoint_id')
                ->setExplanation('Het e-mailadres van de contactpersoon wordt gebruikt voor het versturen van de mail van het sollicitatie-formulier.'),

//            (new MultiSelect())
//                ->setItems($buttonModels->toArray())
//                ->setLabelText(__('KMS::global.button'))
//                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
//                ->setMaxItemsToSelect(1)
//                ->setReference( 'servicepoint_button_id')
        ]);


        $this->tabs->makeLanguageTabTemplate()->addItems([

            (new Title())->setLabelText('SEO'),

            (new TextField())
                ->setLabelText(__('KMS::global.metaTitle'))
                ->setReference( 'meta_title'),

            (new TextArea())
                ->setLabelText(__('KMS::global.metaDescription'))
                ->setReference( 'meta_description'),

            (new Seperator()),

            (new Title())->setLabelText('Informatie'),

            (new TextField())
                ->setLabelText(__('KMS::global.title'))
                ->setReference( 'name'),

            (new TextArea())
                ->setLabelText(__('KMS::global.description'))
                ->setReference( 'description'),

            (new Documents())
                ->setLabelText(__('Afbeelding in de hero'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setMaxDocuments(1)
                ->setSubFolder('vacancies')
                ->setImageProperties([
                    (new ImageProperty())->setName('large')->setCropMethod(ImageProperty::Fit)->setWidth(1152)->setHeight(448),
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Fit)->setWidth(724)->setHeight(282),
                    (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Fit)->setWidth(359)->setHeight(140),
                ])
                ->setReference( 'vacancy_heroes'),

            (new TextField())
                ->setLabelText('Niveau')
                ->setReference( 'level'),

            (new TextField())
                ->setLabelText('Ervaring')
                ->setReference( 'experience'),

            (new TextField())
                ->setLabelText('Salaris')
                ->setReference( 'salary'),

            (new TextField())
                ->setLabelText('Uren')
                ->setReference( 'hours'),



            (new Seperator()),

            (new Title())
                ->setLabelText(__('KMS::global.hero')),

//            (new OnOff())
//                ->setLabelText(__('KMS::global.active'))
//                ->setReference( 'hero_active'),

//            (new TextField())
//                ->setLabelText(__('KMS::global.heroTitle'))
//                ->setReference( 'hero_title'),

            (new TextArea())
                ->enableTinymceEditor()
                ->setLabelText(__('KMS::global.heroDescription'))
                ->setReference( 'hero_description'),

//            (new MultiSelect())
//                ->setItems($buttonModels->toArray())
//                ->setLabelText(__('KMS::global.button'))
//                ->setMaxItemsToSelect(1)
//                ->setReference( 'hero_button_id'),


            (new Seperator()),

            (new Title())->setLabelText('Content blokken'),

            (new ComponentArea())
                ->setSubFolder('vacancy_component_data')
                ->setComponentTypes([
                    ComponentTypes::DOUBLE_USP,
                    ComponentTypes::VACANCY_PROCESS_PERSONAL,
                    ComponentTypes::TEXT_IMAGE_BUTTON,
                    ComponentTypes::USP,
                    ComponentTypes::IMAGE,
                    ComponentTypes::VIDEO,
                    ComponentTypes::TEXT,
                    ComponentTypes::QUOTE,
                    ComponentTypes::CONTENT_SLIDER,
//                    ComponentTypes::CONTENT_PERSONAL,
                ])
                ->setReference( 'vacancy_components'),
        ]);
    }
}