<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CapexPOrelease extends Model
{
    use HasFactory;
    protected $table = 't_capex_porelease';
    protected $primaryKey ='id_capex_porelease';
    public $timestamps = true; // J

    protected $fillable = [
        'id_capex', 
        'description',
        'PO_release',
        
    ];

    public static function get_dtCapexPOrelease()
    {
        $query = DB::table('t_capex_porelease')
            ->select([
                'id_capex_porelease',
                'id_capex',
                'description',
                'PO_release',
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
