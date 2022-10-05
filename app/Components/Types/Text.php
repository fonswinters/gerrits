<?php


namespace App\Components\Types;

use App\Buttons\Kms\ButtonService;
use App\Buttons\Models\Button;
use App\Components\ComponentTypes;
use Komma\KMS\Components\ComponentType\AbstractComponentType as KmsAbstractComponentType;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\TextArea;

class Text extends KmsAbstractComponentType
{
    protected int $id = ComponentTypes::TEXT;
    protected string $name = 'text';

    public function defineAttributesAndTabs()
    {
        $buttonsService = \App::make(ButtonService::class);
        $selectOptions = $buttonsService->getOptionsForSelect(false, true);

        $this->addItems([
            (new TextArea())
                ->setReference('text_text')
                ->enableTinymceEditor()
                ->setLabelText('Tekst'),
        ]);

        $this->addItems([
            (new MultiSelect())
                ->setLabelText('Knop')
                ->setMaxItemsToSelect(1)
                ->setItems($selectOptions->toArray())
                ->canBeLinkedWith(Button::class)
                ->setReference( 'text_buttons'),
        ]);
    }
}