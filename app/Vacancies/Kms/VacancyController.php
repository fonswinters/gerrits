<?php

namespace App\Vacancies\Kms;


use App\Vacancies\Models\VacancyTranslation;
use App\Vacancies\Models\Vacancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Komma\KMS\Core\SectionController;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;

final class VacancyController extends SectionController
{
    protected string $slug = "vacancies";
    protected string $classModelName = Vacancy::class;
    protected ?string $forTranslationModelName = VacancyTranslation::class;

    function __construct()
    {
        $section = new VacancySection($this->slug);
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