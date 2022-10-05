<?php


namespace App\Servicepoints\Kms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;
use Komma\KMS\Core\Sections\Section;

final class ServicepointSection extends Section
{
    public function defineAttributesAndTabs(Model $currentModel = null): void
    {
        $this->tabs->makeTab()->addItems([
            (new TextField())
                ->setLabelText(__('KMS::global.name'))
                ->setReference( 'name'),

            (new Documents())
                ->setLabelText(__('KMS::global.images'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setMaxDocuments(1)
                ->setSubFolder('servicepoints')
                ->setImageProperties([
                    (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Fit)->setWidth(176),
                ])
                ->setReference( 'servicepoints'),
        ]);

        $this->tabs->makeLanguageTabTemplate()->addItems([
            (new TextField())
                ->setLabelText(__('KMS::global.first_name'))
                ->setReference( 'first_name'),

            (new TextField())
                ->setLabelText(__('KMS::global.last_name'))
                ->setReference( 'last_name'),

            (new TextField())
                ->setLabelText(__('KMS::global.function'))
                ->setReference( 'function'),

            (new TextField())
                ->setLabelText(__('KMS::global.telephone_url'))
                ->setReference( 'telephone_url'),

            (new TextField())
                ->setLabelText(__('KMS::global.telephone_label'))
                ->setReference( 'telephone_label'),

            (new TextField())
                ->setLabelText(__('KMS::global.email'))
                ->setReference( 'email'),
        ]);
    }
}