<?php

namespace App\Models;

use Laratrust\Models\Role as LaratrustRole;

class Role extends LaratrustRole
{
    protected $guarded = [];
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}