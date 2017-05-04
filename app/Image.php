<?php
/**
 * Created by PhpStorm.
 * User: Paulina
 * Date: 4/12/2017
 * Time: 3:15 PM
 */

namespace App;

use App\CustomScopes\CustomSoftDelete;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Image extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;

    protected $table = 'image';
    protected $primaryKey = 'id';

    protected $fillable = array('id','id_eks', 'url');

    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new CustomSoftDelete());
    }
}