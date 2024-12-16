<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Approve extends Model
{
    use HasFactory;
    protected $table = 't_approval_report'; // Nama tabel
    protected $primaryKey = 'id_approve'; // Atur primary key jika bukan 'id'
    public $timestamps = true;

    protected $fillable = [
        'id_approve',
        'approver_name',
        'comments',
        'status_capex',
    ];

    public static function getData()
    {
        // Ambil data dengan query builder untuk DataTables
        return DB::table('t_master_capex')
            ->leftJoin('t_approval_report', 't_master_capex.id_capex', '=', 't_approval_report.id_capex')
            ->select(
                't_master_capex.id_capex',
                't_master_capex.status_capex',
                't_approval_report.comments', // Asumsikan ada kolom comments di t_approval_report
                't_master_capex.created_at',
                't_master_capex.updated_at'
            )
            ->where('t_master_capex.status', 1);  // Ambil data yang aktif saja
    }
}
