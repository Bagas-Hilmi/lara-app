<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Models\LaratrustPermission;

class RolePermission extends Model
{
    protected $table = 'permission_role';

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission');
    }
}
