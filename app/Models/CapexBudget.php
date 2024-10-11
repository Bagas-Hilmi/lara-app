<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CapexBudget extends Model
{
    use HasFactory;
    protected $table = 't_capex_budget';

    protected $fillable = [
        'id_capex', 
        'description',
        'budget',
        
    ];

    public static function get_dtCapexBudget()
    {
        $query = DB::table('t_capex_budget')
            ->select([
                'id_capex_budget',
                'id_capex',
                'description',
                'budget',
                'created_at',
                'updated_at'
            ])
            ->orderBy('asc'); // 

        return $query->get(); // Mengambil semua data
    }

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
