<?php

namespace App;

use App\CustomScopes\CustomSoftDelete;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Plan extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
	use SoftDeletes;

	protected $table = 't_plan';
	//protected $primaryKey = 'id';
    public $timestamps = true; //kalo true harus pake created at sm updated at, klo false enggak
	
	protected $fillable = array('id', 'id_store', 'id_user', 'tgl_plan_mulai', 'tgl_plan_selesai', 'jam_mulai', 'jam_selesai');

	public function getDeletedAtColumn()
	{
		return 'flag_del';
	}

	public static function bootSoftDeletes()
	{
		static::addGlobalScope(new CustomSoftDelete());
	}
}
