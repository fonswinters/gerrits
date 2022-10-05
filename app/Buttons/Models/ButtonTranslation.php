<?php
namespace App\Buttons\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Komma\KMS\Core\AbstractTranslationModel;

/**
 * Class ButtonTranslation
 *
 * @package App\Buttons\Models
 * @property int $button_id
 * @property int $language_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string|null $meta_description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation whereButtonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\ButtonTranslation query()
 */
final class ButtonTranslation extends AbstractTranslationModel
{
    protected $table = 'button_translations';

    protected $fillable = ['label', 'url', 'language_id', 'button_id'];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(Button::class, 'button_id');
    }
}
