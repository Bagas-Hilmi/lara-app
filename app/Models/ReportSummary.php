<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

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
        return DB::table('t_master_capex')
            ->leftJoin('t_report_summary', 't_master_capex.id_capex', '=', 't_report_summary.id_capex') 
            ->leftJoin('t_report_cip', 't_master_capex.id_capex', '=', 't_report_cip.id_capex') // Left join dengan t_report_cip untuk mendapatkan amount_rp
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
                DB::raw('SUM(t_report_cip.amount_us) as recost_usd')
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
    }
}
