<?php


namespace App\Components\Types;

use App\Buttons\Kms\ButtonService;
use App\Buttons\Models\Button;
use App\Components\ComponentTypes;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\TextArea;


class DoubleText extends AbstractComponentType
{
    protected int $id = ComponentTypes::DOUBLE_TEXT;
    protected string $name = 'double-text';

    public function defineAttributesAndTabs()
    {
        $buttonsService = \App::make(ButtonService::class);
        $selectOptions = $buttonsService->getOptionsForSelect(false, true);

        $this->addItems([
            (new TextArea())
                ->setReference('double_text_left')
                ->enableTinymceEditor()
                ->setLabelText('Tekst links'),
        ]);

        $this->addItems([
            (new TextArea())
                ->setReference('double_text_right')
                ->enableTinymceEditor()
                ->setLabelText('Tekst rechts'),
        ]);

        $this->addItems([
            (new MultiSelect())
                ->setLabelText('Knop links')
                ->setMaxItemsToSelect(1)
                ->setItems($selectOptions->toArray())
                ->canBeLinkedWith(Button::class)
                ->setReference( 'double_text_button_left'),
        ]);
        $this->addItems([
            (new MultiSelect())
                ->setLabelText('Knop rechts')
                ->setMaxItemsToSelect(1)
                ->setItems($selectOptions->toArray())
                ->canBeLinkedWith(Button::class)
                ->setReference( 'double_text_button_right'),
        ]);
    }
}