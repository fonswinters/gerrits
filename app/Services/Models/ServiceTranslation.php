<?php
namespace App\Services\Models;

use App\Helpers\HasEmptyCheckWithBooleans;
use Komma\KMS\Components\ComponentArea\HasComponentAreasInterface;
use Komma\KMS\Components\ComponentArea\HasComponentAreasTrait;
use Komma\KMS\Core\SuggestSlugTrait;
use Komma\KMS\Core\HasSlugInterface;
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
 * @property-read \App\Services\Models\Service $translatable
 * @mixin \Eloquent
 * @property int $id
 * @property int $service_id
 * @property int $language_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string|null $meta_description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Components\ComponentArea\ComponentArea[] $componentAreas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Services\Models\ServiceTranslation query()
 */
class ServiceTranslation extends AbstractTranslationModel implements DocumentableInterface, HasComponentAreasInterface, HasSlugInterface
{
    use DocumentsTrait;
    use HasComponentAreasTrait;
    use SuggestSlugTrait;
    use HasEmptyCheckWithBooleans;

    private array $booleanAttributes = ['hero_active'];

    protected $table = 'service_translations';

    protected $fillable = ['slug', 'name', 'description', 'meta_description', 'language_id', 'service_id', 'hero_title', 'hero_description', 'hero_active'];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
