<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'permission_role';

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission');
    }
}
