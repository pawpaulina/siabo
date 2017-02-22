<?php

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

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;

    protected $table = 'user';
    protected $primaryKey = 'id_user';

    protected $fillable = ['id_user', 'username', 'password','email','phone','name','id_branch', 'id_role'];

    protected $hidden = ['password'];
	
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}
	
	public function getAuthPassword()
	{
		return $this->password;
	}

	public function getDeletedAtColumn()
    {
        return 'flag_del';
    }

    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new CustomSoftDelete());
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {

    }

    public function getRememberTokenName()
    {
        return null;
    }
    //User Manager Relations
    public function managers(){
        return $this->belongsToMany('App\User', 'user_manager', 'id_user', 'id_manager');
    }
    public function subordinates(){
        return $this->belongsToMany('App\User', 'user_manager', 'id_manager', 'id_user');
    }

}