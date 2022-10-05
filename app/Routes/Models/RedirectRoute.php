<?php
namespace App\Routes\Models;


use App\Routes\RedirectRouteModelInterface;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Routes\Models\RedirectRoute
 * 
 * Class RedirectRoute
 *
 * @package App\Routes\Models
 * @property-read \App\Site\Site $site
 * @mixin \Eloquent
 * @property int $id
 * @property string $route
 * @property string $alias
 * @property int $page_translation_id
 * @property int $site_id
 * @property int $language_id
 * @property int|null $redirect_code
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute whereRedirectCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute whereRouteableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute whereRouteableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute whereUpdatedAt($value)
 * @property-read \App\Pages\Models\PageTranslation $pageTranslation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes\Models\RedirectRoute wherePageTranslationId($value)
 */
class RedirectRoute extends Route implements RedirectRouteModelInterface
{
    const Http10PermanentCanBeCached = 301; //Permanent HTTP/1.0 Redirect. Redirect request may change from GET to POST an vice versa. Can be cached
    const Http10TemporaryNotCachedByDefault = 302; //Temporary HTTP/1.0 Redirect. Redirect request may change from GET to POST an vice versa. Not cached by default
    const Http11TemporaryNeverCached = 303; //Temporary HTTP/1.1 Redirect. Redirect request is always a get request. Cannot be cached
    const Http11TemporaryNotCachedByDefault = 307; //Temporary HTTP/1.1 Redirect. Redirect request is always the same as the initial request (GET or POST). Not cached by default
    const Http11PermanentCachedByDefault = 308; //Permanent HTTP/1.1 Redirect. Redirect request is always the same as the initial request (GET or POST). Cached by default


    protected $table = 'redirect_routes';

    protected $fillable = ['route', 'site_id', 'language_id', 'alias', 'redirect_code', 'routable_type', 'routable_id'];
//    protected $fillable = ['route', 'site_id', 'language_id', 'alias', 'redirect_code', 'page_translation_id'];

    /**
     * Check if the passed redirect code is really a redirect code that is defined as a constant in this class.
     *
     * @param int $redirectCode
     * @param bool $strict If true it will only consider a redirect code as valid if it is an int. If it for example is a numeric string it won't consider it valid
     * @return bool Returns true if the redirect code is a valid one, false otherwise
     * @throws \ReflectionException
     */
    public static function isValidRedirectCode(int $redirectCode, $strict = false) {
        return in_array($redirectCode, self::getAllRedirectCodes(), $strict);
    }


    /**
     * Returns an array containing integers representing the defined redirect codes.
     *
     * @return int[]
     * @throws \ReflectionException
     */
    public static function getAllRedirectCodes()
    {
        $thisClassAsReflectionClass = new \ReflectionClass(__CLASS__);
        return $thisClassAsReflectionClass->getConstants();
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