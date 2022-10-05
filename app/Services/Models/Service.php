<?php


namespace App\Services\Models;

use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelLogicTrait;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Globalization\Languages\Models\Language;
use Komma\KMS\Core\Entities\DisplayNameInterface;
use Komma\KMS\Core\Entities\DisplayNameTrait;
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
 * @property-read \App\Services\Models\ServiceTranslation $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Services\Models\ServiceTranslation[] $translations
 * @mixin \Eloquent
 * @property int $id
 * @property int $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\Service whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\Service whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\Service whereUpdatedAt($value)
 * @property \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $images
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\Service query()
 */
class Service extends AbstractTranslatableModel implements DocumentableInterface, DisplayNameInterface, TreeModelInterface
{
    use DocumentsTrait;
    use DisplayNameTrait;
    use TreeModelLogicTrait;

    protected $table = 'services';
    protected $class = Service::class;

    protected $fillable = ['active', 'site_id'];

    /**
     * Gets the translation models for this model
     *
     * @return HasMany that resolves to AbstractTranslationModel instances
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ServiceTranslation::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'service_translations')
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
}