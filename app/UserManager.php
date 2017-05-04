<?php
/**
 * Created by PhpStorm.
 * User: Paulina
 * Date: 4/17/2017
 * Time: 6:59 PM
 */

namespace App;

use App\CustomScopes\CustomSoftDelete;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class UserManager extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;

    protected $table = 'user_manager';
    protected $primaryKey = 'id_um';
    public $timestamps = false;

    protected $fillable = ['id_manager', 'id_user'];

    public function getDeletedAtColumn()
    {
        return 'flag_del';
    }

    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new CustomSoftDelete());
    }
}