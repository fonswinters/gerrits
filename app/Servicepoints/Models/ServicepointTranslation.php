<?php
namespace App\Servicepoints\Models;

use Komma\KMS\Core\AbstractTranslationModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ServicepointTranslation
 *
 * @package App\Pages\Models
 * @property-read \Komma\KMS\Globalization\Languages\Models\Language $language
 * @property-read \App\Servicepoints\Models\Servicepoint $translatable
 * @property string $first_name
 * @property string $last_name
 * @property string $function
 * @property string $telephone_label
 * @property string $telephone_url
 * @property string $email
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
final class ServicepointTranslation extends AbstractTranslationModel
{
    protected $table = 'servicepoint_translations';

    protected $fillable = ['first_name', 'last_name', 'function', 'telephone_label', 'telephone_url', 'email'];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(Servicepoint::class, 'servicepoint_id');
    }
}
