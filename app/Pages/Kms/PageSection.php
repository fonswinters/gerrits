<?php


namespace App\Pages\Kms;

use Illuminate\Database\Eloquent\Model;
use App\Buttons\Kms\ButtonService;
use App\Components\ComponentTypes;
use App\Pages\Models\Page;
use App\Servicepoints\Kms\ServicepointService;
use App\Servicepoints\Models\Servicepoint;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\ComponentArea;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\SelectOption;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\Select;
use Komma\KMS\Core\ModelServiceInterface;
use Komma\KMS\Core\Sections\Section;
use Komma\KMS\Users\Models\KmsUserRole;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextArea;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;


final class PageSection extends Section
{
    public function defineAttributesAndTabs(Model $currentModel = null): void
    {
        /** @var ModelServiceInterface $pageModelService */
        $pageModelService = app()->make(ModelServiceInterface::class);
        $pageModelService->setModelClassName(Page::class);
        $pageOptionModels = $pageModelService->getOptionsForSelectAsTree();

        /** @var ServicepointService $servicePointService */
        $servicePointService = \App::make(ServicepointService::class);
        $servicePointOptions = $servicePointService->getOptionsForSelect();
        /** @var ButtonService $buttonsService */
        $buttonsService = \App::make(ButtonService::class);
        $buttonModels = $buttonsService->getOptionsForSelect();

        //Get the discover more pages. Except the root page and the current one.
        /** @var ModelServiceInterface $pageModelService */
        $discoverMorePages = $pageModelService->getOptionsForSelect()->filter(function(SelectOption $pageSelectOption) {
//            return $pageSelectOption->getHtmlContent() !== '/' && $pageSelectOption->getHtmlContent() !== $model->getSidebarName(); //Will not because the getSideBarName will not always return the correct name. Needs to be redone properly.
            return $pageSelectOption->getHtmlContent() !== '/';
        });

        $generalTabItems = [
            (new TextField())
                ->setLabelText(__('KMS::global.codeName'))
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setReadOnly(false)
                ->setReference( 'code_name'),

            (new OnOff())
                ->setLabelText(__('KMS::global.active'))
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->switchOn()
                ->setReference( 'active'),

            (new OnOff())
                ->setReference('has_wildcard')
                ->setLabelText(__('KMS::global.uses_wildcard'))
                ->setExplanation(__('KMS::pages.wildcard_explanation'))
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setRules(['sometimes', 'required', 'integer']),

            (new Select())
                ->setItems($pageOptionModels->toArray())
                ->setLabelText(__('KMS::pages.parent_page'))
                ->enableExcludeCurrentModelItem()
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->mapValueFrom(Attribute::ValueFromItself, 'parent_id'),

            (new OnOff())
                ->setLabelText(__('KMS::global.inNav'))
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setReference( 'inNav'),

            (new Seperator())->setMinimumUserRole(KmsUserRole::SuperAdmin),

            (new Documents())
                ->setLabelText(__('KMS::global.discover_thumbnail'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setMaxDocuments(1)
                ->setSubFolder('pages')
                ->setImageProperties([
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Fit)->setWidth(444)->setHeight(296),
                ])
                ->setReference( 'pages'),

            (new Seperator())->setMinimumUserRole(KmsUserRole::SuperAdmin),

            (new Title())
                ->setLabelText(__('KMS::global.servicePoint'))
                ->setMinimumUserRole(KmsUserRole::SuperAdmin),

            (new TextField())
                ->setLabelText('Titel')
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setReference( 'servicepoint_heading'),


            (new MultiSelect())
                ->setItems($servicePointOptions->toArray())
                ->setLabelText(__('KMS::servicepoints.section.title'))
                ->setMaxItemsToSelect(1)
                ->canBeLinkedWith(Servicepoint::class)
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setReference( 'servicepoint_id'),


            (new MultiSelect())
                ->setItems($buttonModels->toArray())
                ->setLabelText(__('KMS::global.button'))
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
                ->setMaxItemsToSelect(1)
                ->setReference( 'servicepoint_button_id'),

            $discoverMoreSeparator = (new Seperator())->setMinimumUserRole(KmsUserRole::SuperAdmin),

            $discoverMoreTitle = (new Title())
                ->setLabelText(__('KMS::pages.discover_more'))
                ->setMinimumUserRole(KmsUserRole::SuperAdmin),

            $discoverMorePageSelector = (new MultiSelect())
                ->setItems($discoverMorePages->toArray())
                ->setLabelText(__('KMS::pages.pages'))
                ->enableSortable()
                ->mapValueFrom(Attribute::ValueFromModelHasManyRelation, 'discoverPages|id')
                ->setMinimumUserRole(KmsUserRole::SuperAdmin)
            ];

        if($currentModel && in_array($currentModel->code_name, ['contact', 'vacancies', 'projects', 'services'], true)) {
            $discoverMoreSeparator->setStyleClass('hidden');
            $discoverMoreTitle->setStyleClass('hidden');
            $discoverMorePageSelector->setStyleClass('hidden');
        }

        $this->tabs->makeTab()->addItems($generalTabItems);

        //Define language attributes
        $languageAttributes = [];

        $languageAttributes[] = (new TextField())
            ->setLabelText(__('KMS::global.title'))
            ->setReference( 'name');

        $languageAttributes[] = (new Seperator());

        $languageAttributes[] = (new Title())->setLabelText('SEO');

        $languageAttributes[] = (new TextField())
            ->setLabelText(__('KMS::global.metaTitle'))
            ->setReference( 'meta_title');

        $languageAttributes[] = (new TextArea())
                ->setLabelText(__('KMS::global.metaDescription'))
                ->setReference( 'meta_description');

        $heroSeperator = (new Seperator())->setMinimumUserRole(KmsUserRole::Admin);
        $languageAttributes[] = $heroSeperator;

        $heroKmsTitle = (new Title())
            ->setLabelText(__('KMS::global.hero'))
            ->setMinimumUserRole(KmsUserRole::Admin);
        $languageAttributes[] = $heroKmsTitle;

        $heroToggle = (new OnOff())
            ->setMinimumUserRole(KmsUserRole::Admin)
            ->setLabelText(__('KMS::global.active'))
            ->setReference( 'hero_active');
        $languageAttributes[] = $heroToggle;

        // Set for which pages (with code_name) we need to disable this field
        if($currentModel && in_array($currentModel->code_name, ['services'], true)) $heroToggle->setStyleClass('hidden');


        $heroTitle = (new TextField())
            ->setLabelText(__('KMS::global.heroTitle'))
            ->setMinimumUserRole(KmsUserRole::Admin)
            ->setReference( 'hero_title');
        $languageAttributes[] = $heroTitle;

        $heroDescription = (new TextArea())
            ->enableTinymceEditor()
            ->setLabelText(__('KMS::global.heroDescription'))
            ->setMinimumUserRole(KmsUserRole::Admin)
            ->setReference( 'hero_description');
        $languageAttributes[] = $heroDescription;

        $heroButtons = (new MultiSelect())
            ->setItems($buttonModels->toArray())
            ->setLabelText(__('KMS::global.button'))
            ->setMinimumUserRole(KmsUserRole::Admin)
            ->setReference( 'hero_button_id');
        $languageAttributes[] = $heroButtons;

        $heroImages = (new Documents())
            ->setLabelText(__('KMS::global.images'))
            ->onlyAllowImages()
            ->setSmallDragAndDropArea()
            ->setMaxDocuments(5)
            ->setMinimumUserRole(KmsUserRole::Admin)
            ->setSubFolder('page_translations')
            ->setImageProperties([
                (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Resize)->setWidth(724),
                (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Resize)->setWidth(359),
            ])
            ->setReference( 'page_translation_heroes');
        $languageAttributes[] = $heroImages;

        if($currentModel && in_array($currentModel->code_name, ['services'], true)) $heroImages->setStyleClass('hidden');

        if($currentModel && $currentModel->code_name == 'home') {
            $heroSeperator->setStyleClass('hidden');
            $heroKmsTitle->setStyleClass('hidden');
            $heroToggle->setStyleClass('hidden');
            $heroTitle->setStyleClass('hidden');
            $heroDescription->setStyleClass('hidden');
            $heroImages->setStyleClass('hidden');
            $heroButtons->setStyleClass('hidden');
        }

        $componentArea = (new ComponentArea())
            ->setComponentTypes([
                ComponentTypes::DOUBLE_TEXT,
                ComponentTypes::VIDEO,
                ComponentTypes::TEXT,
                ComponentTypes::IMAGE,
                ComponentTypes::DOUBLE_IMAGE,
                ComponentTypes::QUOTE,
                ComponentTypes::CONTENT_PERSONAL,
                ComponentTypes::CONTENT_SLIDER,
                ComponentTypes::DOWNLOADS,
                ComponentTypes::TEXT_IMAGE_BUTTON,
                ComponentTypes::USP,
                ComponentTypes::FEATURED_VACANCIES,
            ])
            ->setSubFolder('dynamic_group_sections')
            ->setReference( 'dynamic_group_sections');
        $languageAttributes[] = $componentArea;

        if($currentModel && in_array($currentModel->code_name, ['services'], true)) $componentArea->setStyleClass('hidden');

        $this->tabs->makeLanguageTabTemplate()->addItems($languageAttributes);
    }
}