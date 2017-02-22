<?php

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

class Branch extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;

	protected $table = 'branch';
    protected $primaryKey = 'id_branch';
	 
	protected $fillable = array('id_branch','branch_name');

    public function getDeletedAtColumn()
    {
        return 'flag_del';
    }

    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new CustomSoftDelete());
    }
}
