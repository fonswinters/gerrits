<?php


namespace App\Components\Types;

use App\Buttons\Kms\ButtonService;
use App\Buttons\Models\Button;
use App\Components\ComponentTypes;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;

class USP extends AbstractComponentType
{
    protected int $id = ComponentTypes::USP;
    protected string $name = 'usp';

    const POSSIBLE_AMOUNT = 7;

    public function defineAttributesAndTabs()
    {
        $buttonsService = \App::make(ButtonService::class);
        $selectButtonOptions = $buttonsService->getOptionsForSelect(false, true);

        $this->addItems([
            (new TextField())
                ->setLabelText(__('KMS::attributes/components.title'))
                ->setReference('header'),

            (new Seperator()),

            (new Title())
                ->setLabelText(__('KMS::attributes/components.list')),
        ]);

        for ($i = 0; $i < self::POSSIBLE_AMOUNT; $i++) {
            $this->attributes->push(
                (new TextField())
                    ->setLabelText(__('KMS::attributes/components.line').' '.($i + 1))
                    ->setReference('USP'.($i + 1))
            );
        }

        $this->addItems([
            (new MultiSelect())
                ->setReference('buttons')
                ->setLabelText(__('KMS::global.button'))
                ->setItems($selectButtonOptions->toArray())
                ->setMaxItemsToSelect(1)
                ->canBeLinkedWith(Button::class),

            (new Seperator()),

            (new Title())
                ->setLabelText(__('KMS::attributes/components.image')),

            (new Documents())
                ->onlyAllowImages()
                ->setSubFolder('component_uploads')
                ->setMaxDocuments(1)
                ->setImageProperties([
                    (new ImageProperty())->setName('large')->setCropMethod(ImageProperty::Fit)->setWidth(840),
                ])
                ->setSmallDragAndDropArea()
                ->setReference('image'),
        ]);
    }
}