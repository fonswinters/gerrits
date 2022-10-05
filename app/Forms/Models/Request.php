<?php


namespace App\Forms\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Forms\Models\Request
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $origin
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Forms\Models\Request whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Forms\Models\Request whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Forms\Models\Request whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Forms\Models\Request whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Forms\Models\Request newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Forms\Models\Request newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Forms\Models\Request query()
 */
final class Request extends Model
{
    protected $table = 'requests';
}