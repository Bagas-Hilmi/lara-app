<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Faglb extends Model
{
    use HasFactory;

    protected $table = 't_faglb_head'; // Nama tabel
    protected $primaryKey = 'id_head'; // Atur primary key jika bukan 'id'
    public $timestamps = true; // Jika Anda menggunakan timestamps

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'period',
        'report_status',
        'status',
        'created_by',
        'updated_by'
    ];

    public static function get_dtFaglb()
    {
        $query = DB::table('t_faglb_head')
            ->select([
                'id_head',
                'id_ccb',
                'period',
                'report_status',
                'created_at',
                'updated_at'
            ])
            ->orderBy('period', 'asc'); // Atur berdasarkan kolom 'period', sesuaikan jika perlu

        return $query->get(); // Mengambil semua data

    }

    public static function updateDataFaglb($id_head, $data)
    {
        // Siapkan query untuk memperbarui data
        $query = DB::table('t_faglb_head')
            ->where('id_head', $id_head)
            ->update([
                'id_ccb' => $data['id_ccb'],
                'period' => $data['period'],
                'report_status' => $data['report_status'],
                'updated_at' => now(), // Timestamp untuk updated_at
                'updated_by' => $data['updated_by'], // Pastikan ini ada di $data
            ]);

        return $query; // Mengembalikan hasil query (berhasil atau tidak)
    }

    public function faglbTails()
    {
        return $this->hasMany(FaglbTail::class, 'id_head');
    }

    public function zlis1Tails()
    {
        return $this->hasMany(Zlis1Tail::class, 'id_head');
    }
}
