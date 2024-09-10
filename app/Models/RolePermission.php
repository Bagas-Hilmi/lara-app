<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $connection = 'oee_756';
    protected $table = 'permission_role';

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission');
    }
}
