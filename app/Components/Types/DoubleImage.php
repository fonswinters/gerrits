<?php declare(strict_types=1);


namespace App\Components\Types;


use App\Components\ComponentTypes;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\Title;

class DoubleImage extends AbstractComponentType
{
    protected int $id = ComponentTypes::DOUBLE_IMAGE;
    protected string $name = 'double-image';

    public function defineAttributesAndTabs()
    {
        $this->addItems([
            (new Documents())
                ->setReference('image_image_one')
                ->setLabelText(__('KMS::attributes/components.image'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setSubFolder('component_uploads')
                ->setMaxDocuments(1)
                ->setImageProperties([
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Resize)->setWidth(575),
                    (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Resize)->setWidth(375),
                ]),

            (new Documents())
                ->setReference('image_image_two')
                ->setLabelText(__('KMS::attributes/components.image'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setSubFolder('component_uploads')
                ->setMaxDocuments(1)
                ->setImageProperties([
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Resize)->setWidth(575),
                    (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Resize)->setWidth(375),
                ]),

            (new Seperator()),
            (new Title())
                ->setLabelText(__('KMS::attributes/components.options')),

            (new OnOff())
                ->setReference('image_image_reversed')
                ->setLabelText(__('KMS::attributes/components.swap_double_image')),

        ]);
    }
}