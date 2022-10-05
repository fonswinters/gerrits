<?php
namespace App\Vacancies\Models;

use App\Helpers\HasEmptyCheckWithBooleans;
use Komma\KMS\Components\ComponentArea\HasComponentAreasInterface;
use Komma\KMS\Components\ComponentArea\HasComponentAreasTrait;
use Komma\KMS\Core\HasSlugInterface;
use Komma\KMS\Core\SuggestSlugTrait;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Core\AbstractTranslationModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PageTranslation
 *
 * @package App\Pages\Models
 * @property int page_id
 * @property-read \App\Languages\Models\Language $language
 * @property-read \App\Vacancies\Models\Vacancy $translatable
 * @mixin \Eloquent
 * @property int $id
 * @property int $vacancy_id
 * @property int $language_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string|null $meta_description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation whereVacancyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Components\ComponentArea\ComponentArea[] $componentAreas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacancies\Models\VacancyTranslation query()
 */
class VacancyTranslation extends AbstractTranslationModel implements DocumentableInterface, HasComponentAreasInterface, HasSlugInterface
{
    use DocumentsTrait;
    use HasComponentAreasTrait;
    use SuggestSlugTrait;
    use HasEmptyCheckWithBooleans;

    private array $booleanAttributes = ['hero_active'];

    protected $table = 'vacancy_translations';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'meta_description',
        'language_id',
        'vacancy_id',
        'hero_description',
        'hero_title',
        'hero_active',
        'level',
        'experience',
        'salary',
        'hours',
    ];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(Vacancy::class, 'vacancy_id');
    }
}
