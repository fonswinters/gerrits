<?php
namespace App\Projects\Models;

use App\Helpers\HasEmptyCheckWithBooleans;
use Komma\KMS\Components\ComponentArea\HasComponentAreasInterface;
use Komma\KMS\Components\ComponentArea\HasComponentAreasTrait;
use Komma\KMS\Core\HasSlugInterface;
use Komma\KMS\Core\SuggestSlugTrait;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Core\AbstractTranslationModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Psy\Util\Str;

/**
 * Class PageTranslation
 *
 * @package App\Pages\Models
 * @property int page_id
 * @property-read \App\Languages\Models\Language $language
 * @property-read \App\Projects\Models\Project $translatable
 * @mixin \Eloquent
 * @property int $id
 * @property int $project_id
 * @property int $language_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string|null $meta_description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Components\ComponentArea\ComponentArea[] $componentAreas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Projects\Models\ProjectTranslation query()
 */
class ProjectTranslation extends AbstractTranslationModel implements DocumentableInterface, HasComponentAreasInterface, HasSlugInterface
{
    use DocumentsTrait;
    use HasComponentAreasTrait;
    use SuggestSlugTrait;
    use HasEmptyCheckWithBooleans;

    private array $booleanAttributes = ['hero_active'];

    protected $table = 'project_translations';

    protected $fillable = ['slug', 'name', 'description', 'meta_description', 'language_id', 'project_id', 'hero_description', 'hero_title', 'hero_active'];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
