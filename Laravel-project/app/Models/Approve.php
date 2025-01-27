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
        'wbs_capex',
        'status_capex',
        'requester',
        'file_pdf',
        'upload_by',
        'capex_number',
        'project_desc',
        'cip_number',
        'wbs_number',
        'startup',
        'expected_completed',
        'wbs_type',
        'remark',
        'engineering_production',
        'maintenance',
        'outstanding_inventory',
        'material',
        'jasa',
        'etc',
        'warehouse_received',
        'user_received',
        'berita_acara',
        'signature_detail_file',
        'signature_closing_file',
        'signature_acceptance',
        'upload_date',
        'status_approve_1',
        'status_approve_2',
        'status_approve_3', 
        'status_approve_4', 
        'approved_by_admin_1', 
        'approved_at_admin_1', 
        'approved_by_admin_2', 
        'approved_at_admin_2', 
        'approved_by_user', 
        'approved_at_user', 
        'approved_by_engineer', 
        'approved_at_engineer',
        'amount_budget',
        'budget_type',
        'reason',
    ];

    public static function getData()
    {
        $user = Auth::user(); // Ambil data pengguna yang sedang login

        // Mulai query untuk mengambil data dari t_master_capex
        $query = DB::table('t_master_capex')
            ->leftJoin('t_approval_report', 't_master_capex.id_capex', '=', 't_approval_report.id_capex') // Menyambungkan tabel
            ->whereIn('t_master_capex.status_capex', ['Waiting Approval', 'On Progress', 'To Be Close', 'Close'])
            ->where('t_master_capex.status', 1) 
            ->select(
                't_master_capex.id_capex as id_capex',  // Tambahkan alias yang jelas
                't_master_capex.status_capex',
                't_master_capex.requester',
                't_master_capex.project_desc',
                't_master_capex.wbs_capex',
                't_master_capex.capex_number',
                't_master_capex.wbs_number',
                't_master_capex.expected_completed',
                't_master_capex.startup',
                't_master_capex.cip_number',
                't_master_capex.total_budget',
                't_master_capex.amount_budget',
                't_master_capex.budget_type',

                't_approval_report.file_pdf',
                't_approval_report.file_sap',
                't_approval_report.wbs_type',
                't_approval_report.engineering_production',
                't_approval_report.maintenance',
                't_approval_report.outstanding_inventory',
                't_approval_report.material',
                't_approval_report.jasa',
                't_approval_report.etc',
                't_approval_report.warehouse_received',
                't_approval_report.user_received',
                't_approval_report.berita_acara',
                't_approval_report.signature_detail_file',
                't_approval_report.signature_closing_file',
                't_approval_report.signature_acceptance',
                't_approval_report.upload_date',
                't_approval_report.status_approve_1',
                't_approval_report.status_approve_2',
                't_approval_report.status_approve_3', 
                't_approval_report.status_approve_4', 
                't_approval_report.approved_by_admin_1', 
                't_approval_report.approved_at_admin_1', 
                't_approval_report.approved_by_admin_2', 
                't_approval_report.approved_at_admin_2', 
                't_approval_report.approved_by_user', 
                't_approval_report.approved_at_user', 
                't_approval_report.approved_by_engineer', 
                't_approval_report.approved_at_engineer',
                DB::raw('DATEDIFF(t_approval_report.upload_date, t_master_capex.expected_completed) as time_delay'), // Perhitungan time_delay

            );

        // Jika pengguna bukan admin, tambahkan filter berdasarkan requester
        if ($user->hasRole('admin')) {
            // Admin bisa melihat semua data tanpa filter
            $query;
        } elseif ($user->hasRole('user')) {
            // User hanya dapat melihat data berdasarkan kolom requester yang sesuai dengan nama mereka
            $query->where('t_master_capex.requester', $user->name);
        } elseif ($user->hasRole('engineering')) {
            // Engineer dapat melihat semua data, tetapi hanya dengan wbs_capex yang bernilai 'Project'
            $query->where('t_master_capex.wbs_capex', 'Project');
        }



        $masterData = $query->get(); // Eksekusi query untuk mengambil data
        

        // Perulangan untuk sinkronisasi data ke tabel t_approval_report
        foreach ($masterData as $data) {

            $statusesToCheck = $data->wbs_capex === "Project"
            ? [$data->status_approve_1, $data->status_approve_2, $data->status_approve_3, $data->status_approve_4]
            : [$data->status_approve_1, $data->status_approve_2, $data->status_approve_4];

            if (collect($statusesToCheck)->every(fn($status) => $status == 1)) {
                DB::table('t_master_capex')
                    ->where('id_capex', $data->id_capex)
                    ->update(['status_capex' => 'To Be Close']);
                $data->status_capex = 'To Be Close'; // Perbarui status di objek hasil
            }            

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
                        'wbs_capex' => $data->wbs_capex,
                        'capex_number' => $data->capex_number,
                        'wbs_number' => $data->wbs_number,
                        'expected_completed' => $data->expected_completed,
                        'startup' => $data->startup,
                        'cip_number' => $data->cip_number,
                        'total_budget' => $data->total_budget,
                        'amount_budget' => $data->amount_budget,
                        'budget_type' => $data->budget_type,
                        'time_delay' => $data->time_delay,
                    ]);
            } else {
                // Jika data belum ada, lakukan insert
                DB::table('t_approval_report')->insert([
                    'id_capex' => $data->id_capex,
                    'project_desc' => $data->project_desc,
                    'status_capex' => $data->status_capex,
                    'requester' => $data->requester,
                    'wbs_capex' => $data->wbs_capex,
                    'capex_number' => $data->capex_number,
                    'wbs_number' => $data->wbs_number,
                    'expected_completed' => $data->expected_completed,
                    'startup' => $data->startup,
                    'cip_number' => $data->cip_number,
                    'total_budget' => $data->total_budget,
                    'amount_budget' => $data->amount_budget,
                    'budget_type' => $data->budget_type,
                    'time_delay' => $data->time_delay,


                ]);
            }
        }

        return $masterData; // Kembalikan hasil query
    }

    public static function getStatusCapex()
    {
        $status = DB::table('t_master_capex')
        ->select('status_capex')
        ->where('status', 1)
        ->distinct()
        ->pluck('status_capex')
        ->toArray();

        return $status;
    }

    public function capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
