<?php
namespace App\Posts\Models;

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
 * @property-read \App\Posts\Models\Post $translatable
 * @mixin \Eloquent
 * @property int $id
 * @property int $post_id
 * @property int $language_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string|null $meta_description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Components\ComponentArea\ComponentArea[] $componentAreas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Posts\Models\PostTranslation query()
 */
final class PostTranslation extends AbstractTranslationModel implements HasComponentAreasInterface, HasSlugInterface
{
    use HasComponentAreasTrait;
    use SuggestSlugTrait;

    protected $table = 'post_translations';

    protected $fillable = ['slug', 'name', 'description', 'meta_description', 'meta_title', 'language_id', 'post_id'];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
