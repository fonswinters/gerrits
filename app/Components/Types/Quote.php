<?php declare(strict_types=1);


namespace App\Components\Types;


use App\Components\ComponentTypes;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\TextArea;
use Komma\KMS\Core\Attributes\TextField;

class Quote extends AbstractComponentType
{
    protected int $id = ComponentTypes::QUOTE;
    protected string $name = 'quote';

    public function defineAttributesAndTabs()
    {
        $this->addItems([
            (new TextArea())
                ->setReference('quote_text')
                ->setLabelText(__('KMS::attributes/components.text')),

            (new TextField())
                ->setReference('quote_title')
                ->setLabelText(__('KMS::attributes/components.title')),

            (new TextField())
                ->setReference('quote_subtitle')
                ->setLabelText(__('KMS::attributes/components.job_title')),

            (new Documents())
                ->setReference('quote_image')
                ->setLabelText(__('KMS::attributes/components.image'))
                ->onlyAllowImages()
                ->setSubFolder('component_uploads')
                ->setMaxDocuments(1)
                ->setImageProperties([
                    (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Fit)->setWidth(120),
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Fit)->setWidth(384),
                ])
                ->setSmallDragAndDropArea(),
        ]);
    }
}