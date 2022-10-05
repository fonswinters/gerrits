<?php


namespace App\Components\Types;

use App\Buttons\Kms\ButtonService;
use App\Buttons\Models\Button;
use App\Components\ComponentTypes;
use Komma\KMS\Components\Component\ViewComponent;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextArea;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;

class ContentSlider extends AbstractComponentType
{
    protected int $id = ComponentTypes::CONTENT_SLIDER;
    protected string $name = 'content-slider';

    const POSSIBLE_AMOUNT = 4;

    public function defineAttributesAndTabs()
    {
        $buttonsService = \App::make(ButtonService::class);
        $selectButtonOptions = $buttonsService->getOptionsForSelect(false, true);

        // Make the tabs
        for ($i = 0; $i < self::POSSIBLE_AMOUNT; $i++) {

            $this->addItems([

                (new TextField())
                    ->setLabelText('Tab')
                    ->setReference( 'label_'.($i + 1))
                    ->setDataAttribute('tab', ($i + 1)),

                (new Seperator())
                    ->setDataAttribute('tab', ($i + 1)),

                (new Title())
                    ->setLabelText(__('KMS::attributes/components.text'))
                    ->setDataAttribute('tab', ($i + 1)),

                (new TextField())
                    ->setLabelText(__('KMS::attributes/components.title'))
                    ->setReference( 'header_'.($i + 1))
                    ->setDataAttribute('tab', ($i + 1)),

                (new TextArea())
                    ->enableTinymceEditor()
                    ->setLabelText(__('KMS::attributes/components.text'))
                    ->setReference( 'text_'.($i + 1))
                    ->setDataAttribute('tab', ($i + 1)),

                (new MultiSelect())
                    ->setLabelText(__('KMS::global.button'))
                    ->setItems($selectButtonOptions->toArray())
                    ->setMaxItemsToSelect(1)
                    ->canBeLinkedWith(Button::class)
                    ->setReference( 'buttons_'.($i + 1))
                    ->setDataAttribute('tab', ($i + 1)),

                (new Seperator())
                    ->setDataAttribute('tab', ($i + 1)),

                (new Title())
                    ->setLabelText(__('KMS::attributes/components.image'))
                    ->setDataAttribute('tab', ($i + 1)),

                (new Documents())
                    ->setReference('image_'.($i + 1))
                    ->onlyAllowImages()
                    ->setSubFolder('component_uploads')
                    ->setMaxDocuments(1)
                    ->setImageProperties([
                        (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Resize)->setWidth(724),
                        (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Resize)->setWidth(359),
                    ])
                    ->setSmallDragAndDropArea()
                    ->setDataAttribute('tab', ($i + 1)),

                (new Seperator())
                    ->setDataAttribute('tab', ($i + 1)),
                (new Title())
                    ->setLabelText(__('KMS::attributes/components.options'))
                    ->setDataAttribute('tab', ($i + 1)),

                (new OnOff())
                    ->setLabelText(__('KMS::attributes/components.swap_text_image'))
                    ->setReference( 'reversed_'.($i + 1))
                    ->setDataAttribute('tab', ($i + 1)),

            ]);
        }
    }


    public function prepare(ViewComponent $viewComponent)
    {
        $tabs = collect();
        for ($i = 1; $i <= self::POSSIBLE_AMOUNT; $i++) {
            $label = $viewComponent->{'label_'.$i};
            $header = $viewComponent->{'header_'.$i};
            $text = $viewComponent->{'text_'.$i};
            $buttons = $viewComponent->{'buttons_'.$i};
            $image = $viewComponent->{'image_'.$i};
            $reversed = $viewComponent->{'reversed_'.$i};
            if (!empty($label) || !empty($header) || !empty($text) || !empty($buttons) || !empty($image) || !empty($reversed)) {
                $tab = (object) [
                    'id'       => $i,
                    'label'    => $label,
                    'header'   => $header,
                    'text'     => $text,
                    'buttons'  => $buttons,
                    'image'    => $image,
                    'reversed' => $reversed,
                ];
                $tabs->push($tab);
            }
            unset($viewComponent->{'label_'.$i});
            unset($viewComponent->{'header_'.$i});
            unset($viewComponent->{'text_'.$i});
            unset($viewComponent->{'buttons_'.$i});
            unset($viewComponent->{'image_'.$i});
            unset($viewComponent->{'reversed_'.$i});
        }
        $viewComponent->tabs = $tabs;
    }
}