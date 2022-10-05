<?php

namespace App\TeamMembers\Kms;

/**
 * @author      Komma <info@komma.pro>
 * @copyright   (c) 2012-2016, Komma
 */

use App\TeamMembers\Models\TeamMemberTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\SectionController;
use App\TeamMembers\Models\TeamMember;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;
use Komma\KMS\Sites\HasSiteInterface;

final class TeamMemberController extends SectionController
{
    protected $sortable = true;
    protected string $slug = "team_members";
    protected string $classModelName = TeamMember::class;
    protected ?string $forTranslationModelName = TeamMemberTranslation::class;

    public function __construct()
    {
        $teamMemberSection = new TeamMemberSection($this->slug);
        parent::__construct($teamMemberSection);
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