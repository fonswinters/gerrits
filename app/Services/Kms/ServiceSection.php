<?php


namespace App\Services\Kms;

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
use Komma\KMS\Sites\SiteServiceInterface;
use Komma\KMS\Users\Models\KmsUserRole;
use Illuminate\Support\Collection;

final class ServiceSection extends Section
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

            (new Documents())
                ->setLabelText(__('KMS::global.index_image'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setMaxDocuments(1)
                ->setSubFolder('services')
                ->setImageProperties([
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Fit)->setWidth(444)->setHeight(296),
                ])
                ->setReference( 'services'),

            (new Seperator())->setMinimumUserRole(KmsUserRole::SuperAdmin),

            (new Title())
                ->setLabelText(__('KMS::global.servicePoint'))
                ->setMinimumUserRole(KmsUserRole::SuperAdmin),

            (new TextField())
                ->setLabelText(__('KMS::global.title'))
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setReference( 'servicepoint_heading'),

            (new MultiSelect())
                ->setItems($servicePointOptions->toArray())
                ->setLabelText(__('KMS::servicePoints.section.title'))
                ->setMaxItemsToSelect(1)
                ->canBeLinkedWith(Servicepoint::class)
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setReference( 'servicepoint_id'),

            (new MultiSelect())
                ->setItems($buttonModels->toArray())
                ->setLabelText('Knop')
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setMaxItemsToSelect(1)
                ->setReference( 'servicepoint_button_id')
        ]);

        $this->tabs->makeLanguageTabTemplate()->addItems([
            (new TextField())
                ->setLabelText(__('KMS::global.title'))
                ->setReference( 'name'),

            (new TextField())
                ->setLabelText(__('KMS::global.metaTitle'))
                ->setReference( 'meta_title'),

            (new TextArea())
                ->setLabelText(__('KMS::global.metaDescription'))
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setReference( 'meta_description'),

            (new Seperator())->setMinimumUserRole(KmsUserRole::Admin),

            (new Title())
                ->setLabelText(__('KMS::global.hero'))
                ->setMinimumUserRole(KmsUserRole::Admin),

            (new OnOff())
                ->setLabelText(__('KMS::global.active'))
                ->setMinimumUserRole(KmsUserRole::Admin)
                ->setReference( 'hero_active'),

            (new TextField())
                ->setLabelText(__('KMS::global.heroTitle'))
                ->setMinimumUserRole(KmsUserRole::Admin)
                ->setReference( 'hero_title'),

            (new TextArea())
                ->enableTinymceEditor()
                ->setLabelText(__('KMS::global.heroDescription'))
                ->setMinimumUserRole(KmsUserRole::Admin)
                ->setReference( 'hero_description'),

            (new MultiSelect())
                ->setItems($buttonModels->toArray())
                ->setLabelText('Knop')
                ->setMaxItemsToSelect(1)
                ->setMinimumUserRole(KmsUserRole::Admin)
                ->setReference( 'hero_button_id'),

            (new Documents())
                ->setLabelText(__('KMS::global.images'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setMaxDocuments(5)
                ->setMinimumUserRole(KmsUserRole::Admin)
                ->setSubFolder('services')
                ->setImageProperties([
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Resize)->setWidth(724),
                    (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Resize)->setWidth(359),
                ])
                ->setReference( 'service_translation_heroes'),

            (new ComponentArea())
                ->setSubFolder('service_component_data')
                ->setComponentTypes([
                    ComponentTypes::TEXT_IMAGE_BUTTON,
                    ComponentTypes::VIDEO,
                    ComponentTypes::TEXT,
                    ComponentTypes::IMAGE,
                    ComponentTypes::USP,
                    ComponentTypes::QUOTE,
                    ComponentTypes::CONTENT_PERSONAL,
                    ComponentTypes::CONTENT_SLIDER,
                    ComponentTypes::DOUBLE_TEXT,
                    ComponentTypes::DOUBLE_IMAGE,
                    ComponentTypes::DOWNLOADS,
                ])
                ->setReference( 'service_components'),
        ]);
    }
}