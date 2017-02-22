<?php

namespace App;

use App\CustomScopes\CustomSoftDelete;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use SoftDeletes;

    protected $table = 'role';
    protected $primaryKey = 'id_role';

    protected $fillable = ['id_role', 'role'];

    public function getDeletedAtColumn()
    {
        return 'flag_del';
    }

    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new CustomSoftDelete());
    }
}
