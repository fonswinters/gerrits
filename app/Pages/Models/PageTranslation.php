<?php
namespace App\Pages\Models;

use App\Helpers\HasEmptyCheckWithBooleans;
use App\Routes\HasRoutesInterface;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Komma\KMS\Components\ComponentArea\HasComponentAreasInterface;
use Komma\KMS\Components\ComponentArea\HasComponentAreasTrait;
use Komma\KMS\Core\HasSlugInterface;
use Komma\KMS\Core\SuggestSlugTrait;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Core\AbstractTranslationModel;
use App\Routes\Models\RedirectRoute;
use App\Routes\Models\Route;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Laravel\Scout\Searchable;

/**
 * Class PageTranslation
 *
 * @package App\Pages\Models
 * @property-read \Komma\KMS\Globalization\Languages\Models\Language $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Routes\Models\RedirectRoute[] $redirectRoutes
 * @property-read \App\Routes\Models\Route $route
 * @property-read \App\Pages\Models\Page $translatable
 * @mixin \Eloquent
 * @property int $id
 * @property int $page_id
 * @property int $language_id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Components\ComponentArea\ComponentArea[] $componentAreas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\PageTranslation query()
 */
final class PageTranslation extends AbstractTranslationModel implements HasRoutesInterface, HasComponentAreasInterface, HasSlugInterface, DocumentableInterface
{
    use HasComponentAreasTrait;
    use SuggestSlugTrait;
    use DocumentsTrait;
    use Searchable;
    use SoftDeletes;

    private array $booleanAttributes = ['hero_active'];

    protected $table = 'page_translations';

    protected $fillable = ['slug', 'name', 'description', 'meta_title', 'meta_description', 'language_id', 'page_id', 'hero_title', 'hero_description', 'hero_active', 'hero_button_id'];

    public function translatable():BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

    /**
     * @return MorphOne returns a relation to Route instances
     * @see Route
     */
    public function route(): MorphOne
    {
        return $this->morphOne(Route::class, 'routable');
    }

    /**
     * @return MorphMany returns a relation that resolves to RedirectRouteModelInterface
     * @see RedirectRouteModelInterface
     */
    public function redirectRoutes(): MorphMany
    {
        return $this->morphMany(RedirectRoute::class, 'routable');
    }
}
