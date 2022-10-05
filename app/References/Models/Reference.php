<?php


namespace App\References\Models;

use Komma\KMS\Components\Component\ComponentableInterface;
use Komma\KMS\Components\Component\ComponentableTrait;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\Entities\DisplayNameInterface;
use Komma\KMS\Core\Entities\DisplayNameTrait;
use Komma\KMS\Globalization\Languages\Models\Language;
use Komma\KMS\Sites\HasSiteInterface;
use App\Site\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Class Page
 *
 * @package App\Pages\Models
 * @property int site_id
 * @property int lft
 * @property int rgt
 * @property int tree
 * @property-read \Carbon $date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Languages\Models\Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Site\Site[] $sites
 * @property-read \App\References\Models\ReferenceTranslation $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\References\Models\ReferenceTranslation[] $translations
 * @mixin \Eloquent
 * @property int $id
 * @property int $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\Reference whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\Reference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\Reference whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\Reference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\Reference whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Komma\KMS\Documents\Models\Document[] $images
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\Reference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\Reference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\References\Models\Reference query()
 */
final class Reference extends AbstractTranslatableModel implements ComponentableInterface, DocumentableInterface, DisplayNameInterface
{
    use DocumentsTrait;
    use DisplayNameTrait;
    use ComponentableTrait;

    protected $table = 'references';
    protected $class = Reference::class;

    protected $fillable = ['active', 'name', 'url'];

    /**
     * Gets the translation models for this model
     *
     * @return HasMany that resolves to AbstractTranslationModel instances
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ReferenceTranslation::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'reference_translations')
            ->withPivot('slug', 'name', 'description')
            ->withTimestamps();
    }

    /**
     * Get the site or sites for this model
     *
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the name of this model by model or translation model
     *
     * @return null|string
     */
    public function getDisplayName():?string
    {
        // If it is a new model the section name will filled by model.section.new
        if(!$this->exists) return null;

        // Else detect if the model is a translatableModel
        if(is_a($this, AbstractTranslatableModel::class)){

            if(isset($this->translations) && $this->translations->count() != 0) $modelTranslation = $this->translations->where('language_id', '=','104')->first();

            if(isset($modelTranslation) && isset($modelTranslation->title) && $modelTranslation->title != '')
                return $modelTranslation->title;
        }

        return null;
    }

    /**
     * Get the name for the sidebar
     *
     * @return string
     */
    public function getSidebarName():string
    {
        // If there is no name defined generate a generic name
        if(!$sideBarName = $this->getDisplayName()){
            $sideBarName = trans( 'kms/'.Str::camel($this->table).'.entity') . ' ' . $this->id;
        }

        return $sideBarName;
    }

}