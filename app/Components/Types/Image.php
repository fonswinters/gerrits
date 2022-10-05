<?php declare(strict_types=1);


namespace App\Components\Types;


use App\Components\ComponentTypes;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\TextField;

class Image extends AbstractComponentType
{
    protected int $id = ComponentTypes::IMAGE;
    protected string $name = 'image';

    public function defineAttributesAndTabs()
    {
        $this->addItems([
            (new Documents())
                ->setReference('image_images')
                ->setLabelText(__('KMS::attributes/components.image'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setSubFolder('component_uploads')
                ->setMaxDocuments(5)
                ->setImageProperties([
                    (new ImageProperty())->setName('large')->setCropMethod(ImageProperty::Resize)->setWidth(1152),
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Resize)->setWidth(700),
                    (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Resize)->setWidth(375),
                ]),

            (new TextField())
                ->setReference('image_caption')
                ->setLabelText(__('KMS::attributes/components.image_caption'))
                ->setPlaceholderText(__('KMS::global.optional'))
        ]);
    }
}