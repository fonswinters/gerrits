<?php


namespace App\Pages\Models;

use App\Buttons\Models\Button;
use Komma\KMS\Components\Component\ComponentableInterface;
use Komma\KMS\Components\Component\ComponentableTrait;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelInterface;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModelLogicTrait;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Core\Entities\DisplayNameInterface;
use Komma\KMS\Core\Entities\DisplayNameTrait;
use Komma\KMS\Globalization\Languages\Models\Language;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Komma\KMS\Sites\HasSiteInterface;
use Komma\KMS\Sites\SiteTrait;

/**
 * Class Page
 *
 * @package App\Pages\Models
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Globalization\Languages\Models\Language[] $languages
 * @property-read \App\Pages\Models\PageTranslation $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Pages\Models\PageTranslation[] $translations
 * @mixin \Eloquent
 * @property int $id
 * @property int $site_id
 * @property int $active
 * @property string|null $code_name
 * @property int|null $lft
 * @property int|null $rgt
 * @property int|null $tree
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page whereCodeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page whereTree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page whereUpdatedAt($value)
 * @property \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $images
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Components\Component\Component[] $components
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pages\Models\Page query()
 */
final class Page extends AbstractTranslatableModel implements DocumentableInterface, DisplayNameInterface, ComponentableInterface, HasSiteInterface, TreeModelInterface
{
    use DocumentsTrait;
    use DisplayNameTrait;
    use ComponentableTrait;
    use SiteTrait;
    use TreeModelLogicTrait;

    protected $table = 'pages';
    protected $class = Page::class;

    protected $fillable = ['active', 'site_id', 'lft', 'rgt', 'tree', 'code_name', 'inNav', 'has_wildcard'];

    /**
     * Gets the translation models for this model
     *
     * @return HasMany that resolves to AbstractTranslationModel instances
     */
    public function translations(): HasMany
    {
        return $this->hasMany(PageTranslation::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'page_translations')
            ->withPivot('slug', 'name', 'description')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function discoverPages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'page_discover_pages', 'page_id', 'discover_page_id')
            ->withPivot('sort_order')
            ->orderBy('sort_order');
    }

    /**
     * @return BelongsTo
     */
    public function callToActionButton(): BelongsTo
    {
        return $this->belongsTo(Button::class, 'servicepoint_button_id');
    }
}