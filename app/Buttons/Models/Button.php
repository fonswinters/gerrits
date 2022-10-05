<?php

namespace App\Buttons\Models;

use App\Pages\Models\Page;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Komma\KMS\Core\AbstractTranslatableModel;
use Komma\KMS\Core\Entities\DisplayNameInterface;
use Komma\KMS\Core\Entities\DisplayNameTrait;
use Komma\KMS\Globalization\Languages\Models\Language;

/**
 * Class Button
 *
 * @package App\Buttons\Models
 * @property int $id
 * @property int $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\Button whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\Button whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\Button whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\Button whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\Button newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\Button newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Buttons\Models\Button query()
 */
final class Button extends AbstractTranslatableModel implements DisplayNameInterface
{
    use DisplayNameTrait;

    protected $table = 'buttons';
    protected $class = Button::class;

    protected $fillable = ['active', 'name'];

    /**
     * Gets the translation models for this model
     *
     * @return HasMany that resolves to AbstractTranslationModel instances
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ButtonTranslation::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'button_translations')
            ->withPivot('slug', 'name', 'description')
            ->withTimestamps();
    }

    /**
     * Returns the page the button can have
     *
     * @return Hasone
     */
    public function page(): HasOne
    {
        return $this->hasOne(Page::class);
    }
}