<?php declare(strict_types=1);


namespace App\Components;


use App\Buttons\Models\Button;
use App\Projects\Models\Project;
use App\References\Models\Reference;
use App\Servicepoints\Models\Servicepoint;
use App\Pages\Models\Page;
use App\Services\Models\Service;
use App\TeamMembers\Models\TeamMember;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Komma\KMS\Components\Component\Component as KmsComponent;
use Komma\Kms\Components\ComponentArea\ComponentArea;


class Component extends KmsComponent
{
    /**
     * @return BelongsTo
     */
    public function componentArea(): BelongsTo {
        return $this->belongsTo(ComponentArea::class);
    }

    //Polymorphic relations below this line. Safety goggles on please.
    public function users(): MorphToMany
    {
        return $this->morphedByMany(SiteUser::class, 'componentable');
    }

    public function pages(): MorphToMany
    {
        return $this->morphedByMany(Page::class, 'componentable');
    }

    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'componentable');
    }

    public function projects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, 'componentable');
    }

    public function references(): MorphToMany
    {
        return $this->morphedByMany(Reference::class, 'componentable');
    }

    public function teamMembers(): MorphToMany
    {
        return $this->morphedByMany(TeamMember::class, 'componentable');
    }

    public function buttons(): MorphToMany
    {
        return $this->morphedByMany(Button::class, 'componentable');
    }

    public function servicepoints(): MorphToMany
    {
        return $this->morphedByMany(Servicepoint::class, 'componentable');
    }
}