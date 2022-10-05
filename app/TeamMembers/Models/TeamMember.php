<?php
/**
 *
 *
 * @author      Komma <info@komma.pro>
 * @copyright   (c) 2012-2016, Komma
 */

namespace App\TeamMembers\Models;

use App\Site\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelLogicTrait;
use Komma\KMS\Core\Entities\DisplayNameInterface;
use Komma\KMS\Core\Entities\DisplayNameTrait;
use Komma\KMS\Globalization\Languages\Models\Language;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property-read \App\TeamMembers\Models\TeamMemberTranslation $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TeamMembers\Models\TeamMemberTranslation[] $translations
 * @mixin \Eloquent
 * @property int $id
 * @property int $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMember whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMember whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMember whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $images
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMember query()
 */
final class TeamMember extends AbstractTranslatableModel implements DocumentableInterface, DisplayNameInterface, TreeModelInterface
{
    use DocumentsTrait;
    use DisplayNameTrait;
    use TreeModelLogicTrait;

    protected $class = TeamMember::class;

    protected $fillable = ['active', 'name', 'email', 'linkedinurl', 'lft' , 'rgt' , 'tree'];

    /**
     * Gets the translation models for this model
     *
     * @return HasMany that resolves to AbstractTranslationModel instances
     */
    public function translations(): HasMany
    {
        return $this->hasMany(TeamMemberTranslation::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'team_member_translations')
            ->withPivot('function')
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
        if(!$sidebarName = $this->getDisplayName()){
            return __('kms/teamMembers.entity') . ' '.$this->id;
        }
        return $sidebarName;
    }

}