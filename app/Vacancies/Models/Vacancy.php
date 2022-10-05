<?php


namespace App\Vacancies\Models;

use App\Servicepoints\Models\Servicepoint;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelLogicTrait;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Core\Entities\DisplayNameInterface;
use Komma\KMS\Core\Entities\DisplayNameTrait;
use App\Site\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property-read \App\Vacancies\Models\VacancyTranslation $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vacancies\Models\VacancyTranslation[] $translations
 * @mixin \Eloquent
 * @property int $id
 * @property int $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\Vacancy whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\Vacancy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\Vacancy whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\Vacancy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\Vacancy whereUpdatedAt($value)
 * @property \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $images
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\Vacancy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\Vacancy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\Vacancy query()
 */
class Vacancy extends AbstractTranslatableModel implements DocumentableInterface, DisplayNameInterface, TreeModelInterface
{
    use DocumentsTrait;
    use DisplayNameTrait;
    use TreeModelLogicTrait;

    protected $table = 'vacancies';
    protected $class = Vacancy::class;

    protected $fillable = ['active', 'date', 'site_id'];

    protected $dates = ['date'];

    /**
     * Gets the translation models for this model
     *
     * @return HasMany that resolves to AbstractTranslationModel instances
     */
    public function translations(): HasMany
    {
        return $this->hasMany(VacancyTranslation::class);
    }

    /**
     * Get the site or sites for this model
     *
     * @return BelongsTo
     */
    public function sites(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function servicepoint()
    {
        return $this->belongsTo(Servicepoint::class);
    }
}