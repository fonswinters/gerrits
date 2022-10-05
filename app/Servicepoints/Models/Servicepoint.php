<?php


namespace App\Servicepoints\Models;

use App\Vacancies\Models\Vacancy;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Globalization\Languages\Models\Language;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\Entities\DisplayNameInterface;
use Komma\KMS\Core\Entities\DisplayNameTrait;
use Komma\KMS\Sites\HasSiteInterface;
use App\Site\Site;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Servicepoint
 *
 * @package App\Servicepoints\Models
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Languages\Models\Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Site\Site[] $sites
 * @property-read \App\Servicepoints\Models\ServicepointTranslation $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Servicepoints\Models\ServicepointTranslation[] $translations
 * @mixin \Eloquent
 * @property int $id
 * @property int $active
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\Servicepoint whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\Servicepoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\Servicepoint whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\Servicepoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\Servicepoint whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $images
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\Servicepoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\Servicepoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\Servicepoint query()
 */
final class Servicepoint extends AbstractTranslatableModel implements DocumentableInterface, DisplayNameInterface
{
    use DocumentsTrait;
    use DisplayNameTrait;

    protected $table = 'servicepoints';
    protected $class = Servicepoint::class;

    protected $fillable = ['active', 'name'];

    /**
     * Gets the translation models for this model
     *
     * @return HasMany that resolves to AbstractTranslationModel instances
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ServicepointTranslation::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'servicepoint_translations')
            ->withPivot('slug', 'name', 'description')
            ->withTimestamps();
    }

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }

}