<?php

namespace App\Projects\Kms;


use App\Projects\Models\ProjectTranslation;
use App\Projects\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\SectionController;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;
use Komma\KMS\Sites\HasSiteInterface;

final class ProjectController extends SectionController
{
    protected string $slug = "projects";
    protected string $classModelName = Project::class;
    protected ?string $forTranslationModelName = ProjectTranslation::class;

    function __construct()
    {
        $section = new ProjectSection($this->slug);
        parent::__construct($section);
    }

    protected function save(Model $model, Collection $attributesByValueFrom = null): Model
    {
        /** @var AbstractTranslatableModel|HasSiteInterface|TreeModelInterface $model */
        if(!$model->exists) {
            $rootModel = $this->classModelName::allRoot()->first();
            $model->makeLastChildOf($rootModel);
        }
        $model = parent::save($model, $attributesByValueFrom);

        return $model;
    }
}