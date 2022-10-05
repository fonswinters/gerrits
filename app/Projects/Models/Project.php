<?php


namespace App\Projects\Models;

use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelLogicTrait;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Core\Entities\DisplayNameInterface;
use Komma\KMS\Core\Entities\DisplayNameTrait;
use Komma\KMS\Globalization\Languages\Models\Language;
use App\Site\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Komma\KMS\Sites\HasSiteInterface;
use Komma\KMS\Sites\SiteTrait;


/**
 * Class Page
 *
 * @package App\Pages\Models
 * @property int site_id
 * @property int lft
 * @property int rgt
 * @property int tree
 * @property-read \Carbon $date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Languages\Models\Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Site\Site[] $sites
 * @property-read \App\Projects\Models\ProjectTranslation $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Projects\Models\ProjectTranslation[] $translations
 * @mixin \Eloquent
 * @property int $id
 * @property int $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\Project whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\Project whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\Project whereUpdatedAt($value)
 * @property \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $images
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\Project query()
 */
class Project extends AbstractTranslatableModel implements DocumentableInterface, DisplayNameInterface, TreeModelInterface
{
    use DocumentsTrait;
    use DisplayNameTrait;
    use TreeModelLogicTrait;

    protected $table = 'projects';
    protected $class = Project::class;

    protected $fillable = ['active', 'date', 'site_id'];

    protected $dates = ['date'];

    /**
     * Gets the translation models for this model
     *
     * @return HasMany that resolves to AbstractTranslationModel instances
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ProjectTranslation::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'project_translations')
            ->withPivot('slug', 'name', 'description')
            ->withTimestamps();
    }

    /**
     * Get the site or sites for this model
     *
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the name for the sidebar
     *
     * @return string
     */
    public function getSidebarName():string
    {
        // If there is no name defined generate a generic name
        if(!$sideBarName = $this->getDisplayName()){
            $sideBarName = trans( 'KMS::'.Str::camel($this->table).'.entity') . ' ' . $this->id;
        }

        return $sideBarName;
    }
}