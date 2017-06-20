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

class TugasPokok extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;

    protected $table = 'tugas_pokok';
    protected $primaryKey = 'id';

    protected $fillable = array('judul', 'deskripsi', 'foto', 'exp_date');

    public function getDeletedAtColumn()
    {
        return 'deleted_at';
    }

    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new CustomSoftDelete());
    }
}
