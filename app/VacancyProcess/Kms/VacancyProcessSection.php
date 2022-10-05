<?php


namespace App\VacancyProcess\Kms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\TextArea;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;
use Komma\KMS\Core\Sections\Section;

final class VacancyProcessSection extends Section
{
    public function defineAttributesAndTabs(Model $currentModel = null): void
    {
        $this->tabs->makeLanguageTabTemplate()->addItems([
            (new TextField())
                ->setLabelText(__('KMS::global.title'))
                ->setReference( 'name'),

            (new TextArea())
                ->setLabelText(__('KMS::global.description'))
                ->setReference( 'description'),
        ]);
    }
}