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

class ToDo extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;

    protected $table = 'to_do';
    protected $primaryKey = 'id';

    protected $fillable = array('id', 'judul_tugas', 'deskripsi_tugas', 'keterangan', 'id_bukti', 'id_user', 'id_to_do');

    public function getDeletedAtColumn()
    {
        return 'flag';
    }

    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new CustomSoftDelete());
    }
}
