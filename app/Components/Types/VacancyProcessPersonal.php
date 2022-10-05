<?php


namespace App\Components\Types;

use App\Components\ComponentTypes;
use App\VacancyProcess\Models\VacancyProcess;
use Komma\KMS\Components\Component\ViewComponent;
use App\Servicepoints\Kms\ServicepointService;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextField;

class VacancyProcessPersonal extends AbstractComponentType
{

    protected int $id = ComponentTypes::VACANCY_PROCESS_PERSONAL;
    protected string $name = 'vacancy-process-personal';

    public function defineAttributesAndTabs()
    {
        $servicePointService = \App::make(ServicepointService::class);
        $selectServicePointOptions = $servicePointService->getOptionsForSelect(false, true);

        $this->addItems([

            (new TextField())
                ->setReference('title')
                ->setLabelText(__('KMS::attributes/components.title')),

            (new Seperator()),

            (new TextField())
                ->setReference('servicepoint_title')
                ->setLabelText('CTA ' . __('KMS::attributes/components.title')),

//            (new MultiSelect())
//                ->setReference('servicepoint')
//                ->setLabelText(__('KMS::attributes/components.contact'))
//                ->setItems($selectServicePointOptions->toArray())
//                ->setMaxItemsToSelect(1)
//                ->canBeLinkedWith(Servicepoint::class),

        ]);
    }

    public function prepare(ViewComponent $viewComponent)
    {
//        $servicePoint = $viewComponent->servicepoint->first();
//
//        if(isset($servicePoint)) {
//            $servicePoint->load('translations', 'documents');
//            $viewComponent->servicepoint = $servicePoint;
//        }

        $viewComponent->process = VacancyProcess::where('lft', '!=', 1)
            ->orderBy('lft')
            ->get();

    }
}