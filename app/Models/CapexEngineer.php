<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CapexEngineer extends Model
{
    use HasFactory;
    protected $table = 't_capex_engineer';
    protected $primaryKey = 'id_engineer';

    protected $fillable = ['nama', 'created_by', 'updated_by'];

    public static function get_dtEngineer()
    {
        $query = DB::table('t_capex_engineer')
            ->select([
                'id_capex',
                'nama',
                'created_at',
                'updated_at'
            ])
            ->orderBy('asc'); // 

        return $query->get(); // Mengambil semua data
    }

    public static function addEngineer($data)
    {
        // Masukkan data completion baru ke dalam tabel menggunakan query builder
        $engineerId = DB::table('t_capex_engineer')->insertGetId([
            'id_capex' => $data['id_capex'],
            'nama' => $data['nama'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mengembalikan data completion yang baru ditambahkan
        return DB::table('t_capex_engineer')->where('id_engineer', $engineerId)->first();
    }
}
