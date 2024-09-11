<?php

namespace App\Models;

use Laratrust\Models\Role as LaratrustRole;
use Illuminate\Support\Facades\DB;

class Role extends LaratrustRole
{
    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    public function permission_role()
    {
        return $this->hasMany('App\Models\RolePermission');
    }

    public function getRoles()
    {
        $getData = DB::table('roles')->get();
        return $getData;
    }
}
