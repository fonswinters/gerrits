<?php

namespace App\VacancyProcess\Kms;

use App\VacancyProcess\Models\VacancyProcess;
use App\VacancyProcess\Models\VacancyProcessTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Komma\KMS\Core\SectionController;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;

final class VacancyProcessController extends SectionController
{
    protected string $slug = "vacancy_process";
    protected string $classModelName = VacancyProcess::class;
    protected ?string $forTranslationModelName = VacancyProcessTranslation::class;

    function __construct()
    {
        $section = new VacancyProcessSection($this->slug);
        parent::__construct($section);
    }

    protected function save(Model $model, Collection $attributesByValueFrom = null): Model
    {
        /** @var TreeModelInterface $model */
        if(!$model->exists) {
            $rootModel = $this->classModelName::allRoot()->first();
            $model->makeLastChildOf($rootModel);
        }
        $model = parent::save($model, $attributesByValueFrom);

        return $model;
    }
}