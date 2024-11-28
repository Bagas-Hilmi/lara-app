<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnSelf;

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

    public static function addPORelease($data)
    {
        // Masukkan data baru ke dalam tabel menggunakan query builder
        $poreleaseId = DB::table('t_capex_porelease')->insertGetId([
            'id_capex' => $data['id_capex'],
            'description' => $data['description'],
            'po_release' => $data['po_release'],
            'created_at' => now(), // Jika Anda ingin mencatat waktu pembuatan
            'updated_at' => now(), // Jika Anda ingin mencatat waktu pembaruan
        ]);

        // Mengembalikan data yang baru ditambahkan
        return DB::table('t_capex_porelease')->where('id_capex_porelease', $poreleaseId)->first();
    }

    public static function editPORelease($id, $data)
    {
        // Temukan PO Release berdasarkan ID dan perbarui data menggunakan query builder
        DB::table('t_capex_porelease')
            ->where('id_capex_porelease', $id)
            ->update([
                'id_capex' => $data['id_capex'],
                'description' => $data['description_porelease'],
                'po_release' => $data['po_release'],
                'updated_at' => now(), // Jika Anda ingin mencatat waktu pembaruan
            ]);

        // Mengembalikan data yang diperbarui
        return DB::table('t_capex_porelease')->where('id_capex_porelease', $id)->first();
    }

    public static function getStatus($id, $status = null)
    {
        $query = DB::table('t_capex_porelease')
            ->join('t_master_capex', 't_capex_porelease.id_capex', '=', 't_master_capex.id_capex')
            ->select('t_capex_porelease.*', 't_master_capex.status_capex');

        if ($id) {
            $query->where('t_capex_porelease.id_capex', $id); // Filter berdasarkan ID
        }

        if (!is_null($status)) {
            $query->where('t_capex_porelease.status', $status); // Tambahkan filter status jika diberikan
        }
        
        return $query->get();
    }

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
