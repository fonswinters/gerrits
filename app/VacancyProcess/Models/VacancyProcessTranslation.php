<?php
namespace App\VacancyProcess\Models;

use Komma\KMS\Core\AbstractTranslationModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ServicepointTranslation
 *
 * @package App\Pages\Models
 * @property-read \Komma\KMS\Globalization\Languages\Models\Language $language
 * @property-read \App\Servicepoints\Models\Servicepoint $translatable
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\ServicepointTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\ServicepointTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\ServicepointTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\ServicepointTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\ServicepointTranslation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\ServicepointTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\ServicepointTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicepoints\Models\ServicepointTranslation query()
 */
final class VacancyProcessTranslation extends AbstractTranslationModel
{
    protected $table = 'vacancy_process_translations';

    protected $fillable = ['name', 'description', 'language_id', 'vacancy_process_id',];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(VacancyProcess::class, 'vacancy_process_id');
    }
}
