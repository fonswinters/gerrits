<?php


namespace App\Events\Kms;

//The new object oriented attributes
use App\Attributes\TimePicker;
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
use Komma\KMS\Core\Attributes\Title;
use Komma\KMS\Core\Sections\Section;

final class EventSection extends Section
{
    public function defineAttributesAndTabs(Model $currentModel = null): void
    {
        $this->tabs->makeTab()->addItems([
            (new OnOff())
                ->setLabelText(__('KMS::global.active'))
                ->switchOn()
                ->setReference( 'active'),

            (new DatePicker())
                ->setLabelText('Begin datum & tijd')
                ->setTimeEnabled(true)
                ->setReference( 'datetime_start'),

            (new DatePicker())
                ->setLabelText('Eind datum & tijd')
                ->setTimeEnabled(true)
                ->setReference( 'datetime_end'),

//            (new TextField())
//                ->setLabelText(__('Tijd'))
//                ->setReference( 'time'),

            (new Documents())
                ->setLabelText(__('KMS::global.index_image'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setMaxDocuments(1)
                ->setImageProperties([
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Fit)->setWidth(264)->setHeight(280),
                ])
                ->setSubFolder('events')
                ->setReference( 'events')
        ]);

        //Build an array with attributes for each current site language
        $this->tabs->makeLanguageTabTemplate()->addItems([

            (new Title())->setLabelText('SEO'),

            (new TextField())
                ->setLabelText(__('KMS::global.metaTitle'))
                ->setReference( 'meta_title'),

            (new TextArea())
                ->setLabelText(__('KMS::global.metaDescription'))
                ->setReference( 'meta_description'),

            (new Seperator()),

            (new Title())->setLabelText('Informatie'),

            (new TextField())
                ->setLabelText(__('KMS::global.title'))
                ->setReference( 'name'),

            (new TextField())
                ->setLabelText(__('Locatie'))
                ->setReference( 'location'),

            (new TextField())
                ->setLabelText(__('Kosten'))
                ->setReference( 'costs'),

            (new TextField())
                ->setLabelText(__('Toelichting'))
                ->setReference( 'description'),


            (new Seperator()),

            (new Title())->setLabelText('Content blokken'),

            (new ComponentArea())
                ->setSubFolder('event_component_data')
                ->setComponentTypes([
                    ComponentTypes::TEXT_IMAGE,
                    ComponentTypes::DOUBLE_TEXT,
                    ComponentTypes::DOUBLE_IMAGE,
                    ComponentTypes::QUOTE,
                    ComponentTypes::TEXT,
                    ComponentTypes::IMAGE,
                    ComponentTypes::VIDEO,
                ])
                ->setReference( 'event_components')
        ]);
    }
}