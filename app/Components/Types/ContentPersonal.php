<?php


namespace App\Components\Types;

use App\Buttons\Kms\ButtonService;
use App\Buttons\Models\Button;
use App\Components\ComponentTypes;
use Komma\KMS\Components\Component\ViewComponent;
use App\Servicepoints\Kms\ServicepointService;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Seperator;
use App\Servicepoints\Models\Servicepoint;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\TextArea;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;

class ContentPersonal extends AbstractComponentType
{

    protected int $id = ComponentTypes::CONTENT_PERSONAL;
    protected string $name = 'content-personal';

    public function defineAttributesAndTabs()
    {
        $servicePointService = \App::make(ServicepointService::class);
        $selectServicePointOptions = $servicePointService->getOptionsForSelect(false, true);

        $buttonsService = \App::make(ButtonService::class);
        $selectButtonOptions = $buttonsService->getOptionsForSelect(false, true);

        $this->addItems([
            (new Title())
                ->setLabelText(__('KMS::attributes/components.text')),

            (new TextField())
                ->setReference('contentHeader')
                ->setLabelText(__('KMS::attributes/components.title')),

            (new TextArea())
                ->setReference('contentDescription')
                ->enableTinymceEditor()
                ->setLabelText(__('KMS::attributes/components.text')),

            (new MultiSelect())
                ->setReference('contentButtons')
                ->setLabelText(__('KMS::global.button'))
                ->setItems($selectButtonOptions->toArray())
                ->setMaxItemsToSelect(1)
                ->canBeLinkedWith(Button::class),

            (new Seperator()),
            (new Title())
                ->setLabelText(__('KMS::attributes/components.contact')),

            (new TextField())
                ->setReference('servicePointHeader')
                ->setLabelText(__('KMS::attributes/components.title')),

            (new MultiSelect())
                ->setReference('servicepoints')
                ->setLabelText(__('KMS::attributes/components.contact'))
                ->setItems($selectServicePointOptions->toArray())
                ->setMaxItemsToSelect(1)
                ->canBeLinkedWith(Servicepoint::class),

            (new MultiSelect())
                ->setReference('servicePointButtons')
                ->setLabelText(__('KMS::global.button'))
                ->setItems($selectButtonOptions->toArray())
                ->setMaxItemsToSelect(1)
                ->canBeLinkedWith(Button::class),
        ]);
    }

    public function prepare(ViewComponent $viewComponent)
    {
        $viewComponent->servicepoints->load('translations', 'documents');
    }
}