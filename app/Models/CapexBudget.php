<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapexBudget extends Model
{
    use HasFactory;
    protected $table = 't_capex_budget';

    protected $fillable = [
        'id_capex', 
        'description',
        'budget',
        
    ];

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
