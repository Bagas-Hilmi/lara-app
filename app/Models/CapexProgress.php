<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CapexProgress extends Model
{
    use HasFactory;
    protected $table = 't_capex_progress';
    protected $primaryKey ='id_capex_progress';
    public $timestamps = true; // 

    protected $fillable = [
        'id_capex', 
        'tanggal', 
        'description',
        
    ];

    public static function get_dtCapexProgress()
    {
        $query = DB::table('t_capex_progress')
            ->select([
                'id_capex_progress',
                'id_capex',
                'tanggal',
                'description',
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