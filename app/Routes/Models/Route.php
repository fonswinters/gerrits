<?php

namespace App\Routes\Models;

use App\Routes\RouteModelInterface;
use App\Site\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Route
 *
 * @package App\Routes\Models
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $routeable
 * @property-read \App\Site\Site $site
 * @mixin \Eloquent
 * @property int $id
 * @property string $route
 * @property string $alias
 * @property int $page_translation_id
 * @property string $routeable_type
 * @property int $site_id
 * @property int $language_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route whereRouteableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route whereRouteableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route whereUpdatedAt($value)
 * @property-read \App\Pages\Models\PageTranslation $pageTranslation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\Route wherePageTranslationId($value)
 */
class Route extends Model implements RouteModelInterface
{
    protected $table = 'routes';

    protected $fillable = ['route', 'site_id', 'language_id', 'alias', 'routable_type', 'routable_id'];

    /**
     * @mixin \Eloquent
     * @package App\Routes
     */
    public function routable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site(){
        return $this->belongsTo(Site::class);
    }

    /**
     * Mutator to ensure route is always lowercase
     *
     * @see https://laravel.com/docs/6.x/eloquent-mutators#defining-a-mutator
     * @param $value
     */
    public function setRouteAttribute($value) {
        $this->attributes['route'] = strtolower($value);
    }

    /**
     * Mutator to ensure alias is always lowercase
     *
     * @see https://laravel.com/docs/6.x/eloquent-mutators#defining-a-mutator
     * @param $value
     */
    public function setAliasAttribute($value) {
        $this->attributes['alias'] = strtolower($value);
    }
}