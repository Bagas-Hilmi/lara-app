<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportSummary extends Model
{
    use HasFactory;
    protected $table = 't_report_summary';
    protected $primaryKey = 'id_report_summary';
    public $timestamps = true;

    protected $fillable = [
        'id_capex',
        'project_desc',
        'wbs_capex',
        'category',
        'remark',
        'request_number',
        'requester',
        'capex_number',
        'amount_budget',
        'budget_cos',
        'total_budget',
        'recost_rp',
        'recost_usd',
        'PO_release',
        'realized',
        'budget_type',
        'status_capex',
        'startup',
        'expected_completed',
        'wbs_number',
        'cip_number',
    ];

    public static function getMasterdata()
    {
        try {
            // Mulai transaksi database untuk memastikan konsistensi data
            DB::beginTransaction();

            // Ambil data master capex dengan join ke tabel lain
            $masterData = DB::table('t_master_capex')
                ->leftJoin('t_report_summary', 't_master_capex.id_capex', '=', 't_report_summary.id_capex')
                ->leftJoin('t_report_cip', 't_master_capex.id_capex', '=', 't_report_cip.id_capex')
                ->select(
                    't_master_capex.id_capex',
                    't_master_capex.project_desc',
                    't_master_capex.wbs_capex',
                    't_master_capex.category',
                    't_master_capex.remark',
                    't_master_capex.request_number',
                    't_master_capex.requester',
                    't_master_capex.capex_number',
                    't_master_capex.amount_budget',
                    't_master_capex.budget_cos',
                    't_master_capex.total_budget',
                    't_master_capex.PO_release',
                    't_master_capex.budget_type',
                    't_master_capex.status_capex',
                    't_master_capex.startup',
                    't_master_capex.expected_completed',
                    't_master_capex.days_remaining',
                    't_master_capex.days_late',
                    't_master_capex.revise_completion_date',
                    't_master_capex.wbs_number',
                    't_master_capex.cip_number',
                    't_report_summary.realized',
                    DB::raw('SUM(t_report_cip.amount_rp) as recost_rp'),
                    DB::raw('COALESCE(SUM(t_report_cip.amount_us), 0) as recost_usd'),
                    DB::raw('COALESCE(t_master_capex.total_budget, 0) as total_budget'),
                    DB::raw('ROUND((COALESCE(SUM(t_report_cip.amount_us), 0) / COALESCE(t_master_capex.total_budget, 0)) * 100, 2) as realized_percentage')
                    )
                ->where('t_master_capex.status', 1)
                ->groupBy(
                    't_master_capex.id_capex',
                    't_master_capex.project_desc',
                    't_master_capex.wbs_capex',
                    't_master_capex.category',
                    't_master_capex.remark',
                    't_master_capex.request_number',
                    't_master_capex.requester',
                    't_master_capex.capex_number',
                    't_master_capex.amount_budget',
                    't_master_capex.budget_cos',
                    't_master_capex.total_budget',
                    't_master_capex.PO_release',
                    't_master_capex.budget_type',
                    't_master_capex.status_capex',
                    't_master_capex.startup',
                    't_master_capex.expected_completed',
                    't_master_capex.days_remaining',
                    't_master_capex.days_late',
                    't_master_capex.revise_completion_date',
                    't_master_capex.wbs_number',
                    't_master_capex.cip_number',
                    't_report_summary.realized'
                )
                ->distinct()
                ->get();

            // Proses setiap data master
            foreach ($masterData as $data) {
                // Cek apakah record sudah ada di t_report_summary
                $existingRecord = DB::table('t_report_summary')
                    ->where('id_capex', $data->id_capex)
                    ->first();

                // Data yang akan disimpan/diupdate
                $reportData = [
                    'id_capex' => $data->id_capex,
                    'project_desc' => $data->project_desc,
                    'wbs_capex' => $data->wbs_capex,
                    'category' => $data->category,
                    'remark' => $data->remark,
                    'request_number' => $data->request_number,
                    'requester' => $data->requester,
                    'capex_number' => $data->capex_number,
                    'amount_budget' => $data->amount_budget,
                    'budget_cos' => $data->budget_cos,
                    'total_budget' => $data->total_budget,
                    'recost_rp' => $data->recost_rp ?? 0,
                    'recost_usd' => $data->recost_usd ?? 0,
                    'PO_release' => $data->PO_release,
                    'realized' => $data->realized_percentage,
                    'budget_type' => $data->budget_type,
                    'status_capex' => $data->status_capex,
                    'startup' => $data->startup,
                    'expected_completed' => $data->expected_completed,
                    'days_remaining' => $data->days_remaining,
                    'days_late' => $data->days_late,
                    'revise_completion_date' => $data->revise_completion_date,
                    'wbs_number' => $data->wbs_number,
                    'cip_number' => $data->cip_number,
                    'created_at' => now(), 
                    'updated_at' => now()
                ];

                if ($existingRecord) {
                    // Update record yang sudah ada
                    DB::table('t_report_summary')
                        ->where('id_capex', $data->id_capex)
                        ->update($reportData);
                } else {
                    // Insert record baru
                    DB::table('t_report_summary')
                        ->insert($reportData);
                }
            }

            // Commit transaksi jika semua proses berhasil
            DB::commit();

            // Kembalikan data master
            return $masterData;
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error
            Log::error('Gagal memproses master data: ' . $e->getMessage());

            // Throw exception atau return false sesuai kebutuhan
            throw new \Exception('Gagal memproses master data: ' . $e->getMessage());
        }
    }
}
