<?php
namespace App\Events\Models;

use App\Components\ComponentArea\ComponentArea;
use Illuminate\Database\Eloquent\Collection;
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
 * @property-read \Komma\KMS\Globalization\Languages\Models\Language $language
 * @property-read \App\Events\Models\Event $translatable
 * @mixin \Eloquent
 * @property int $id
 * @property int $event_id
 * @property int $language_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string|null $meta_description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Collection|ComponentArea[] $componentAreas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events\Models\EventTranslation query()
 */
final class EventTranslation extends AbstractTranslationModel implements HasComponentAreasInterface, HasSlugInterface
{
    use HasComponentAreasTrait;
    use SuggestSlugTrait;

    protected $table = 'event_translations';

    protected $fillable = ['slug', 'name', 'description', 'costs', 'location', 'meta_description', 'meta_title', 'language_id', 'event_id'];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
