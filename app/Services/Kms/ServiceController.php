<?php

namespace App\Services\Kms;


use App\Services\Models\ServiceTranslation;
use App\Services\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\SectionController;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;

final class ServiceController extends SectionController
{
    protected string $slug = "services";
    protected string $classModelName = Service::class;
    protected ?string $forTranslationModelName = ServiceTranslation::class;

    function __construct()
    {
        $section = new ServiceSection($this->slug);
        parent::__construct($section);
    }

    protected function save(Model $model, Collection $attributesByValueFrom = null): Model
    {
        /** @var AbstractTranslatableModel|TreeModelInterface $model */
        if(!$model->exists) {
            $rootModel = $this->classModelName::allRoot()->first();
            $model->makeLastChildOf($rootModel);
        }
        $model = parent::save($model, $attributesByValueFrom);

        return $model;
    }
}