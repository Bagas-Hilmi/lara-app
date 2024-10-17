<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CapexPOrelease extends Model
{
    use HasFactory;
    protected $table = 't_capex_porelease';
    protected $primaryKey = 'id_capex_porelease';
    public $timestamps = true; // J

    protected $fillable = [
        'id_capex',
        'description',
        'PO_release',

    ];

    public static function get_dtCapexPOrelease()
    {
        // Mengambil semua data dari t_capex_porelease
        $query = DB::table('t_capex_porelease')
            ->select([
                'id_capex_porelease',
                'id_capex',
                'description',
                'PO_release',
                'created_at',
                'updated_at'
            ])
            ->where('status', 1) // Tambahkan kondisi untuk status
            ->get();

        foreach ($query as $item) {
            // Hitung total PO_release berdasarkan id_capex
            $totalPORelease = DB::table('t_capex_porelease')
                ->where('id_capex', $item->id_capex)
                ->where('status', 1) // Pastikan statusnya juga 1
                ->sum('PO_release');

            // Update PO_release di t_master_capex
            DB::table('t_master_capex')
                ->where('id_capex', $item->id_capex)
                ->update(['PO_release' => $totalPORelease]);
        }

        return $query; // Mengembalikan semua data
    }

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
