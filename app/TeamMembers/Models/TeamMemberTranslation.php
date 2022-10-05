<?php
namespace App\TeamMembers\Models;

use Komma\KMS\Components\ComponentArea\HasComponentAreasInterface;
use Komma\KMS\Components\ComponentArea\HasComponentAreasTrait;
use Komma\KMS\Core\AbstractTranslationModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PageTranslation
 *
 * @package App\Pages\Models
 * @property int page_id
 * @property-read \Komma\KMS\Globalization\Languages\Models\Language $language
 * @property-read \App\TeamMembers\Models\TeamMember $translatable
 * @mixin \Eloquent
 * @property int $id
 * @property int $teamMember_id
 * @property int $language_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string|null $meta_description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation whereTeamMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Components\ComponentArea\ComponentArea[] $componentAreas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamMembers\Models\TeamMemberTranslation query()
 */
final class TeamMemberTranslation extends AbstractTranslationModel implements HasComponentAreasInterface
{
    use HasComponentAreasTrait;

    protected $fillable = ['function', 'language_id', 'team_member_id'];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }
}
