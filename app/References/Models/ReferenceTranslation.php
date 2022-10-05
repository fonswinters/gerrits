<?php
namespace App\References\Models;

use App\Helpers\HasEmptyCheckWithBooleans;
use Komma\KMS\Components\ComponentArea\HasComponentAreasInterface;
use Komma\KMS\Components\ComponentArea\HasComponentAreasTrait;
use Komma\KMS\Core\AbstractTranslationModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PageTranslation
 *
 * @package App\Pages\Models
 * @property int page_id
 * @property-read \Komma\KMS\Globalization\Languages\Models\Language $language
 * @property-read \App\References\Models\Reference $translatable
 * @mixin \Eloquent
 * @property int $id
 * @property int $reference_id
 * @property int $language_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string|null $meta_description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Components\ComponentArea\ComponentArea[] $componentAreas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\ReferenceTranslation query()
 */
final class ReferenceTranslation extends AbstractTranslationModel implements HasComponentAreasInterface
{
    use HasComponentAreasTrait;
    use HasEmptyCheckWithBooleans;

    private array $booleanAttributes = ['hero_active'];

    protected $table = 'reference_translations';

    protected $fillable = ['slug', 'title', 'subtitle', 'quote', 'language_id', 'reference_id'];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }
}
