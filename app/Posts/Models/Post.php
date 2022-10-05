<?php
namespace App\Posts\Models;

use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Globalization\Languages\Models\Language;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\Entities\DisplayNameInterface;
use Komma\KMS\Core\Entities\DisplayNameTrait;
use App\Site\Site;
use App\Users\Models\SiteUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Komma\KMS\Helpers\KommaHelpers;
use Komma\KMS\Sites\HasSiteInterface;

/**
 * Class Page
 *
 * @package App\Pages\Models
 * @property int site_id
 * @property int lft
 * @property int rgt
 * @property int tree
 * @property-read Carbon $date
 * @property-read \Komma\KMS\Globalization\Languages\Models\Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Site\Site[] $sites
 * @property-read \App\Posts\Models\PostTranslation $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Posts\Models\PostTranslation[] $translations
 * @mixin \Eloquent
 * @property int $id
 * @property int $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\Post whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\Post whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\Post whereUpdatedAt($value)
 * @property \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $images
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\Post query()
 */
final class Post extends AbstractTranslatableModel implements DocumentableInterface, DisplayNameInterface
{
    use DocumentsTrait;
    use DisplayNameTrait {
        getDisplayName as traitGetDisplayName;
    }

    protected $table = 'posts';
    protected $class = Post::class;

    protected $fillable = ['active', 'date'];

    protected $dates = ['date'];

    /**
     * Gets the translation models for this model
     *
     * @return HasMany that resolves to AbstractTranslationModel instances
     */
    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'post_translations')
            ->withPivot('slug', 'name', 'description')
            ->withTimestamps();
    }

    /**
     * Get the name for the sidebar
     *
     * @return string
     */
    public function getSidebarName():string
    {
        // If there is no name defined generate a generic name
        $sidebarName = $this->traitGetDisplayName();

        $sidebarName .= '<br/><sub>' . $this->date->format('d-m-Y') . '</sub>';

        return $sidebarName;
    }
}