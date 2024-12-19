<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Approve extends Model
{
    use HasFactory;
    protected $table = 't_approval_report'; // Nama tabel
    protected $primaryKey = 'id_approve'; // Atur primary key jika bukan 'id'
    public $timestamps = true;

    protected $fillable = [
        'id_capex',
        'project_desc',
        'status_capex',
        'requester',
        'file_pdf'
    ];

    public static function getData()
    {
        $user = Auth::user(); // Ambil data pengguna yang sedang login

        // Mulai query untuk mengambil data dari t_master_capex
        $query = DB::table('t_master_capex')
            ->leftJoin('t_approval_report', 't_master_capex.id_capex', '=', 't_approval_report.id_capex') // Menyambungkan tabel
            ->where('t_master_capex.status_capex', 'On Progress')
            ->where('t_master_capex.status', 1) // Ambil data yang aktif saja 
            ->select(
                't_master_capex.id_capex',
                't_master_capex.status_capex',
                't_master_capex.requester',
                't_master_capex.project_desc',
                't_approval_report.file_pdf', 
                't_approval_report.signature_file', 
                't_approval_report.upload_date', 
                't_approval_report.status_approve_1', 
                't_approval_report.status_approve_2', 
                't_approval_report.status_approve_3' // Alihkan nama kolom untuk menghindari duplikasi
            );

        // Jika pengguna bukan admin, tambahkan filter berdasarkan requester
        if (!$user->hasRole(['admin', 'engineer'])) {
            $query->where('t_master_capex.requester', $user->name); // Filter data untuk user biasa
        }

        $masterData = $query->get(); // Eksekusi query untuk mengambil data

        // Perulangan untuk sinkronisasi data ke tabel t_approval_report
        foreach ($masterData as $data) {
            // Cek apakah data sudah ada di t_approval_report
            $existingRecord = DB::table('t_approval_report')
                ->where('id_capex', $data->id_capex)
                ->first();

            if ($existingRecord) {
                // Jika data sudah ada, lakukan update
                DB::table('t_approval_report')
                    ->where('id_capex', $data->id_capex)
                    ->update([
                        'project_desc' => $data->project_desc,
                        'status_capex' => $data->status_capex,
                        'requester' => $data->requester,
                    ]);
            } else {
                // Jika data belum ada, lakukan insert
                DB::table('t_approval_report')->insert([
                    'id_capex' => $data->id_capex,
                    'project_desc' => $data->project_desc,
                    'status_capex' => $data->status_capex,
                    'requester' => $data->requester,
                ]);
            }
        }

        return $masterData; // Kembalikan hasil query
    }
}
