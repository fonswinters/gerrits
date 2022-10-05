<?php declare(strict_types=1);


namespace App\Components\Types;


use App\Buttons\Kms\ButtonService;
use App\Buttons\Models\Button;
use App\Components\ComponentTypes;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextArea;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;

class TextImage extends AbstractComponentType
{

    protected int $id = ComponentTypes::TEXT_IMAGE;
    protected string $name = 'text-image';

    public function defineAttributesAndTabs()
    {
        $buttonsService = \App::make(ButtonService::class);
        $selectOptions = $buttonsService->getOptionsForSelect(false, true);

        $this->addItems([
            (new Title())
                ->setLabelText(__('KMS::attributes/components.text')),

            (new TextArea())
                ->setReference('text_image_text')
                ->enableTinymceEditor(),

            (new MultiSelect())
                ->setLabelText('Knop')
                ->setMaxItemsToSelect(1)
                ->setItems($selectOptions->toArray())
                ->canBeLinkedWith(Button::class)
                ->setReference( 'text_image_buttons'),

            (new Seperator()),
            (new Title())
                ->setLabelText(__('KMS::attributes/components.image')),

            (new Documents())
                ->setReference('text_image_images')
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
                ->setReference('text_image_caption')
                ->setLabelText(__('KMS::attributes/components.image_caption')),

            (new Seperator()),
            (new Title())
                ->setLabelText(__('KMS::attributes/components.options')),

            (new OnOff())
                ->setReference('text_image_reversed')
                ->setLabelText(__('KMS::attributes/components.swap_text_image')),
        ]);
    }
}