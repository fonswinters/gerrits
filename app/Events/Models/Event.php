<?php
namespace App\Events\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Documents\Models\Document;
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
 * @property-read Language[] $languages
 * @property-read Collection|\App\Site\Site[] $sites
 * @property-read EventTranslation $translation
 * @property-read Collection|EventTranslation[] $translations
 * @mixin \Eloquent
 * @property int $id
 * @property int $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static Builder|Event whereActive($value)
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereDate($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @property Collection|Document[] $documents
 * @property-read Collection|Document[] $images
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static Builder|Event query()
 */
final class Event extends AbstractTranslatableModel implements DocumentableInterface, DisplayNameInterface
{
    use DocumentsTrait;
    use DisplayNameTrait {
        getDisplayName as traitGetDisplayName;
    }

    protected $table = 'events';
    protected $class = Event::class;

    protected $fillable = ['active', 'datetime_start', 'datetime_end'];

    protected $dates = ['datetime_start', 'datetime_end'];

    /**
     * Gets the translation models for this model
     *
     * @return HasMany that resolves to AbstractTranslationModel instances
     */
    public function translations(): HasMany
    {
        return $this->hasMany(EventTranslation::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'event_translations')
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

        $sidebarName .= '<br/><sub>' . $this->datetime_start->format('d-m-Y') . '</sub>';

        return $sidebarName;
    }
}