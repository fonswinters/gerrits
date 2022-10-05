<?php


namespace App\Posts\Kms;

//The new object oriented attributes
use Illuminate\Database\Eloquent\Model;
use App\Components\ComponentTypes;
use Komma\KMS\Core\Attributes\ComponentArea;
use Komma\KMS\Core\Attributes\DatePicker;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextArea;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Sections\Section;

final class PostSection extends Section
{
    public function defineAttributesAndTabs(Model $currentModel = null): void
    {
        $this->tabs->makeTab()->addItems([
            (new OnOff())
                ->setLabelText(__('KMS::global.active'))
                ->switchOn()
                ->setReference( 'active'),

            (new DatePicker())
                ->setLabelText(__('KMS::global.date'))
                ->setTimeEnabled(false)
                ->setReference( 'date'),

            (new Documents())
                ->setLabelText(__('KMS::global.index_image'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setMaxDocuments(1)
                ->setSubFolder('posts')
                ->setReference( 'posts')
                ->setImageProperties([
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Fit)->setWidth(600),
                ])
        ]);

        //Build an array with attributes for each current site language
        $this->tabs->makeLanguageTabTemplate()->addItems([
            (new TextField())
                ->setLabelText(__('KMS::global.title'))
                ->setReference( 'name'),

            (new TextArea())
                ->setLabelText(__('KMS::global.shortDescription'))
                ->setReference( 'short_description'),

            (new Seperator()),

            (new TextField())
                ->setLabelText(__('KMS::global.metaTitle'))
                ->setReference( 'meta_title'),

            (new TextArea())
                ->setLabelText(__('KMS::global.metaDescription'))
                ->setReference( 'meta_description'),

            (new ComponentArea())
                ->setSubFolder('post_component_data')
                ->setComponentTypes([
                    ComponentTypes::TEXT,
                    ComponentTypes::IMAGE,
                    ComponentTypes::VIDEO,
                ])
                ->setReference( 'post_components')
        ]);
    }
}