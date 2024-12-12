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
        'status_capex',
        'cap_date',
        'cap_doc',
        'no_asset',
    ];

    public static function getData()
    {
        // Mengambil data dari join tabel
        $masterData = DB::table('t_master_capex')
            ->leftJoin('t_report_tax', 't_master_capex.id_capex', '=', 't_report_tax.id_capex')
            ->leftJoin('t_report_cip', 't_master_capex.id_capex', '=', 't_report_cip.id_capex')
            ->select(
                't_master_capex.id_capex',
                't_master_capex.project_desc',
                't_master_capex.capex_number',
                't_master_capex.cip_number',
                't_master_capex.wbs_number',
                't_master_capex.total_budget',
                't_master_capex.status_capex',
                't_report_cip.material',
                't_report_cip.description',
                't_report_cip.qty',
                't_report_cip.uom',
                't_report_cip.amount_rp',
                't_report_cip.amount_us',
                't_report_cip.date',
                't_report_cip.settle_doc',
                't_report_cip.fa_doc',
                't_report_tax.cap_date',
                't_report_tax.cap_doc',
                't_report_tax.no_asset',
            )
            ->where('t_master_capex.status', 1) // Opsional: filter hanya data aktif
            ->get();

        // Proses simpan ke tabel t_report_tax
        foreach ($masterData as $data) {
            // Cek apakah data dengan id_capex sudah ada di t_report_tax
            $existingRecord = DB::table('t_report_tax')
                ->where('id_capex', $data->id_capex)
                ->first();

            if ($existingRecord) {
                // Jika sudah ada, lakukan update
                DB::table('t_report_tax')
                    ->where('id_capex', $data->id_capex)
                    ->update([
                        'project_desc' => $data->project_desc,
                        'capex_number' => $data->capex_number,
                        'cip_number' => $data->cip_number,
                        'wbs_number' => $data->wbs_number,
                        'total_budget' => $data->total_budget,
                        'material' => $data->material,
                        'description' => $data->description,
                        'qty' => $data->qty,
                        'uom' => $data->uom,
                        'amount_rp' => $data->amount_rp,
                        'amount_us' => $data->amount_us,
                        'date' => $data->date,
                        'settle_doc' => $data->settle_doc,
                        'fa_doc' => $data->fa_doc,
                        'status_capex' => $data->status_capex,
                    ]);
            } else {
                // Jika belum ada, lakukan insert
                DB::table('t_report_tax')
                    ->insert([
                        'id_capex' => $data->id_capex,
                        'project_desc' => $data->project_desc,
                        'capex_number' => $data->capex_number,
                        'cip_number' => $data->cip_number,
                        'wbs_number' => $data->wbs_number,
                        'total_budget' => $data->total_budget,
                        'material' => $data->material,
                        'description' => $data->description,
                        'qty' => $data->qty,
                        'uom' => $data->uom,
                        'amount_rp' => $data->amount_rp,
                        'amount_us' => $data->amount_us,
                        'date' => $data->date,
                        'settle_doc' => $data->settle_doc,
                        'fa_doc' => $data->fa_doc,
                        'status_capex' => $data->status_capex,
                    ]);
            }
        }

        return $masterData;
    }

    public static function getStatus()
    {
        $status = DB::table(('t_master_capex'))
            ->select('status_capex')
            ->where('status', 1)
            ->distinct()
            ->pluck('status_capex')
            ->toArray();

        return $status;
    }
}
