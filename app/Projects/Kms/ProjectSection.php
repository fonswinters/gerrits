<?php


namespace App\Projects\Kms;

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

final class ProjectSection extends Section
{
    public function defineAttributesAndTabs(Model $currentModel = null): void
    {
        /** @var ServicepointService $servicePointService */
        $servicePointService = \App::make(ServicepointService::class);
        $servicePointOptions = $servicePointService->getOptionsForSelect(false, true);

        /** @var ButtonService $buttonsService */
        $buttonsService = \App::make(ButtonService::class);
        $buttonModels = $buttonsService->getOptionsForSelect();

        $attributes = [];

        $attributes[] = (new OnOff())
            ->setLabelText(__('KMS::global.active'))
            ->switchOn()
            ->setReference( 'active');

        $attributes[] = (new Documents())
            ->setLabelText(__('KMS::global.index_image'))
            ->onlyAllowImages()
            ->setSmallDragAndDropArea()
            ->setMaxDocuments(1)
            ->setSubFolder('projects')
            ->setImageProperties([
                (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Fit)->setWidth(444)->setHeight(296),
            ])
            ->setReference( 'projects');

        $attributes[] = (new Seperator())->setMinimumUserRole(KmsUserRole::SuperAdmin);

        $attributes[] = (new Title())
            ->setLabelText(__('KMS::global.servicePoint'))
            ->setMinimumUserRole(KmsUserRole::SuperAdmin);

        $attributes[] = (new TextField())
            ->setLabelText(__('KMS::global.title'))
            ->setMinimumUserRole(KmsUserRole::SuperAdmin)
            ->setReference( 'servicepoint_heading');

        $attributes[] = (new MultiSelect())
            ->setItems($servicePointOptions->toArray())
            ->setLabelText(__('KMS::servicepoints.section.title'))
            ->setMaxItemsToSelect(1)
            ->canBeLinkedWith(Servicepoint::class)
            ->setMinimumUserRole(KmsUserRole::SuperAdmin)
            ->setReference( 'servicepoint_id');

        $attributes[] = (new MultiSelect())
            ->setItems($buttonModels->toArray())
            ->setLabelText(__('KMS::global.button'))
            ->setMinimumUserRole(KmsUserRole::SuperAdmin)
            ->setMaxItemsToSelect(1)
            ->setReference( 'servicepoint_button_id');

        $this->tabs->makeTab()->addItems($attributes);

        //Build an array with attributes for each current site language
        $this->tabs->makeLanguageTabTemplate()->addItems([
            (new Title())
                ->setLabelText(__('KMS::projects.entity')),

            (new TextField())
                ->setLabelText(__('KMS::global.title'))
                ->setReference( 'name'),

            (new TextField())
                ->setLabelText(__('KMS::global.metaTitle'))
                ->setReference( 'meta_title'),

            (new TextArea())
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setLabelText(__('KMS::global.metaDescription'))
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
                ->setLabelText(__('KMS::global.button'))
                ->setMaxItemsToSelect(1)
                ->setMinimumUserRole(KmsUserRole::Admin)
                ->setReference( 'hero_button_id'),

            (new Documents())
                ->setLabelText(__('KMS::global.images'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setMaxDocuments(5)
                ->setMinimumUserRole(KmsUserRole::Admin)
                ->setSubFolder('projects')
                ->setImageProperties([
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Resize)->setWidth(724),
                    (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Resize)->setWidth(359),
                ])
                ->setReference( 'project_heroes'),

            (new ComponentArea())
                ->setSubFolder('project_component_data')
                ->setComponentTypes([
                    ComponentTypes::TEXT,
                    ComponentTypes::IMAGE,
                    ComponentTypes::TEXT_IMAGE_BUTTON,
                    ComponentTypes::VIDEO,
                    ComponentTypes::USP,
                    ComponentTypes::QUOTE,
                    ComponentTypes::CONTENT_PERSONAL,
                    ComponentTypes::CONTENT_SLIDER,
                    ComponentTypes::DOUBLE_TEXT,
                    ComponentTypes::DOUBLE_IMAGE,
                    ComponentTypes::DOWNLOADS,
                ])
                ->setReference( 'project_components'),
        ]);
    }
}