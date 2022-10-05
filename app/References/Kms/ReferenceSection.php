<?php


namespace App\References\Kms;

use Illuminate\Database\Eloquent\Model;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\TextArea;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Sections\Section;

final class ReferenceSection extends Section
{
    public function defineAttributesAndTabs(Model $currentModel = null): void
    {
        $this->tabs->makeTab()->addItems([
            (new OnOff())
                ->setLabelText(__('KMS::global.active'))
                ->switchOn()
                ->setReference( 'active'),

            (new Documents())
                ->setLabelText(__('KMS::global.images'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setMaxDocuments(1)
                ->setSubFolder('references')
                ->setImageProperties([
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Resize)->setWidth(438),
                ])
                ->setReference( 'references')
        ]);

        $this->tabs->makeLanguageTabTemplate()->addItems([
            (new TextArea())
                ->setLabelText(__('KMS::global.quote'))
                ->setReference( 'quote'),

            (new TextField())
                ->setLabelText(__('KMS::global.title'))
                ->setReference( 'title'),

            (new TextField())
                ->setLabelText(__('KMS::global.subtitle'))
                ->setReference( 'subtitle'),

            (new TextField())
                ->setLabelText(__('KMS::global.website'))
                ->setPlaceholderText(__('KMS::global.optional'))
                ->setReference( 'url'),
        ]);
    }
}