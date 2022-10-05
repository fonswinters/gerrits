<?php


namespace App\Components\Types;

use App\Buttons\Kms\ButtonService;
use App\Buttons\Models\Button;
use App\Components\ComponentTypes;
use App\Vacancies\VacancyService;
use Komma\KMS\Components\Component\ViewComponent;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\Numeric;
use Komma\KMS\Core\Attributes\TextField;

class FeaturedVacancies extends AbstractComponentType
{
    protected int $id = ComponentTypes::FEATURED_VACANCIES;
    protected string $name = 'featured-vacancies';

    public function defineAttributesAndTabs()
    {
        $buttonsService = \App::make(ButtonService::class);
        $selectOptions = $buttonsService->getOptionsForSelect(false, true);
        
        $this->addItems([
            (new TextField())
                ->setLabelText(__('KMS::attributes/components.title'))
                ->setReference('header'),

            (new Numeric())
                ->setLabelText('Aantal vacatures')
                ->setValue(3)
                ->setWholeMin(1)
                ->setWholeMax(8)
                ->setReference('amount_of_vacancies')
                ->setExplanation('Alleen actieve vacatures aflopend'),

            (new MultiSelect())
                ->setLabelText('Knop')
                ->setMaxItemsToSelect(1)
                ->setItems($selectOptions->toArray())
                ->canBeLinkedWith(Button::class)
                ->setReference( 'button'),

        ]);
    }

    public function prepare(ViewComponent $viewComponent)
    {

        $amountOfVacancies = (int) $viewComponent->amount_of_vacancies;

        /** @var VacancyService $vacancyService */
        $vacancyService = app(VacancyService::class);
        $viewComponent->vacancies = $vacancyService->getAmountOfVacancies($amountOfVacancies);

        return $viewComponent;
    }
}