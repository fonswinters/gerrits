<?php


namespace App\Components\Types;

use App\Buttons\Kms\ButtonService;
use App\Buttons\Models\Button;
use App\Components\ComponentTypes;
use Komma\KMS\Components\Component\ViewComponent;
use Komma\KMS\Components\ComponentType\AbstractComponentType as KmsAbstractComponentType;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;
use App\Servicepoints\Kms\ServicepointService;
use App\Servicepoints\Models\Servicepoint;

class Downloads extends KmsAbstractComponentType
{
    protected int $id = ComponentTypes::DOWNLOADS;
    protected string $name = 'downloads';

    public function defineAttributesAndTabs()
    {
        $servicePointService = \App::make(ServicepointService::class);
        $selectServicePointOptions = $servicePointService->getOptionsForSelect(false, true);

        $buttonsService = \App::make(ButtonService::class);
        $selectButtonOptions = $buttonsService->getOptionsForSelect(false, true);

        $this->addItems([

            (new TextField())
                ->setLabelText(__('KMS::attributes/components.title'))
                ->setReference('download_title'),

            (new Title())
                ->setLabelText('Downloads'),

            (new Documents())
                ->setReference('downloads')
                ->setSubFolder('component_downloads')
                ->setMaxDocuments(12),

            (new Seperator()),
            (new Title())
                ->setLabelText(__('KMS::attributes/components.contact')),

            (new TextField())
                ->setLabelText('Titel')
                ->setReference('personal_header'),

            (new MultiSelect())
                ->setLabelText(__('KMS::attributes/components.contact'))
                ->setItems($selectServicePointOptions->toArray())
                ->setMaxItemsToSelect(1)
                ->canBeLinkedWith(Servicepoint::class)
                ->setReference('servicepoints'),

            (new MultiSelect())
                ->setLabelText(__('KMS::global.button'))
                ->setItems($selectButtonOptions->toArray())
                ->setMaxItemsToSelect(1)
                ->canBeLinkedWith(Button::class)
                ->setReference('servicePointButtons'),

            (new Seperator()),

            (new Title())
                ->setLabelText(__('KMS::attributes/components.options')),

            (new OnOff())
                ->setLabelText(__('KMS::attributes/components.swap_download_contact'))
                ->setReference('reversed'),

        ]);
    }

    public function prepare(ViewComponent $viewComponent)
    {
        $viewComponent->downloads = $viewComponent->downloads->sortBy('sort_order');
        return $viewComponent;
    }
}