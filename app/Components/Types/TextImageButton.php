<?php


namespace App\Components\Types;

use App\Buttons\Kms\ButtonService;
use App\Buttons\Models\Button;
use App\Components\ComponentTypes;
use Komma\KMS\Components\Component\ViewComponent;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextArea;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;


class TextImageButton extends AbstractComponentType
{
    protected int $id = ComponentTypes::TEXT_IMAGE_BUTTON;
    protected string $name = 'text-image';

    public function defineAttributesAndTabs()
    {
        $buttonsService = \App::make(ButtonService::class);
        $selectOptions = $buttonsService->getOptionsForSelect(false, true);
        
        $this->addItems([
            (new Title())
                ->setLabelText(__('KMS::attributes/components.text')),

            (new TextArea())
                ->enableTinymceEditor()
                ->setReference( 'text'),

            (new MultiSelect())
                ->setLabelText('Knop')
                ->setMaxItemsToSelect(1)
                ->setItems($selectOptions->toArray())
                ->canBeLinkedWith(Button::class)
                ->setReference( 'buttons'),

            (new Seperator()),
            (new Title())
                ->setLabelText(__('KMS::attributes/components.image')),

            (new Documents())
                ->setReference('image')
                ->onlyAllowImages()
                ->setSubFolder('component_uploads')
                ->setMaxDocuments(5)
                ->setImageProperties([
                    (new ImageProperty())->setName('large')->setCropMethod(ImageProperty::Resize)->setWidth(900),
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Resize)->setWidth(740),
                    (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Resize)->setWidth(375),
                ])
                ->setSmallDragAndDropArea(),

            (new TextField())
                ->setLabelText(__('KMS::attributes/components.image_caption'))
                ->setReference( 'caption'),
        
            (new Seperator()),
            (new Title())
                ->setLabelText(__('KMS::attributes/components.options')),

            (new OnOff())
                ->setLabelText(__('KMS::attributes/components.swap_text_image'))
                ->setReference( 'reversed'),
        ]);
    }

    public function prepare(ViewComponent $viewComponent)
    {
        $viewComponent->view = 'organisms.componentables.textImage';
    }

}