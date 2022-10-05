<?php


namespace App\Components\Types;

use App\Components\ComponentTypes;
use Komma\KMS\Components\Component\ViewComponent;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextField;

class DoubleUSP extends AbstractComponentType
{
    protected int $id = ComponentTypes::DOUBLE_USP;
    protected string $name = 'double-usp';

    const POSSIBLE_AMOUNT = 5;

    public function defineAttributesAndTabs()
    {
        for ($i = 0; $i < 2; $i++) {

            $this->addItems([
                (new TextField())
                    ->setLabelText(__('KMS::attributes/components.title'))
                    ->setReference('header_' . ($i + 1))
                    ->setDataAttribute('tab', ($i + 1)),
                (new Seperator())->setDataAttribute('tab', ($i + 1))
            ]);

            for ($j = 0; $j < self::POSSIBLE_AMOUNT; $j++) {
                $this->addItems([
                    (new TextField())
                        ->setLabelText('USP '.($j + 1))
                        ->setReference('USP_'.($i + 1).'_'.($j + 1))
                        ->setDataAttribute('tab', ($i + 1))
                ]);
            }
        }
    }

    public function prepare(ViewComponent $viewComponent)
    {

        for ($i = 0; $i < 2; $i++) {

            $items = collect();
            for($j = 0; $j <= self::POSSIBLE_AMOUNT; $j++) {
                $usp = $viewComponent->{'USP_' . ($i + 1).'_'.($j + 1)};

                if(!empty($usp) ) {
                    $items->push($usp);
                }
                unset($viewComponent->{'USP_' . ($i + 1).'_'.($j + 1)});
            }

            $sideAttributes = [
                'header' => $viewComponent->{'header_' . ($i + 1)},
                'items' => $items,
            ];

            unset($viewComponent->{'header_' . ($i + 1)});

            if($i == 0 ) $viewComponent->left = $sideAttributes;
            else $viewComponent->right = $sideAttributes;
        }

    }
}