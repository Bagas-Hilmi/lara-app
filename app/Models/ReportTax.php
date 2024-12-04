<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class ReportTax extends Model
{
    use HasFactory;
    protected $table = 't_report_tax';
    protected $primaryKey = 'id_report_tax';
    public $timestamps = true;

    protected $fillable = [
        'id_capex',
        'project_desc',
        'capex_number',
        'cip_number',
        'wbs_number',
        'material',
        'description',
        'total_budget',
        'qty',
        'uom',
        'amount_rp',
        'amount_us',
        'date',
        'settle_doc',
        'fa_doc',
    ];

    public static function getData()
    {
        return DB::table('t_master_capex')
            ->leftJoin('t_report_tax', 't_master_capex.id_capex', '=', 't_report_tax.id_capex')
            ->leftJoin('t_report_cip', 't_master_capex.id_capex', '=', 't_report_cip.id_capex')
            ->select(
                't_master_capex.project_desc',
                't_master_capex.capex_number',
                't_master_capex.cip_number',
                't_master_capex.wbs_number',
                't_master_capex.total_budget',
                't_report_cip.material',
                't_report_cip.description',
                't_report_cip.qty',
                't_report_cip.uom',
                't_report_cip.amount_rp',
                't_report_cip.amount_us',
                't_report_cip.date',
                't_report_cip.settle_doc',
                't_report_cip.fa_doc',
            )
            ->where('t_master_capex.status', 1) // Opsional: filter hanya data aktif
            ->get();
    }
}
