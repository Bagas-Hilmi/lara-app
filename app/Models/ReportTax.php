<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        try {
            DB::beginTransaction();

            // Mengambil data dari join tabel
            $query = DB::table('t_master_capex')
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
                    't_master_capex.cap_date',
                    't_master_capex.cap_doc',
                    't_master_capex.no_asset',
                    't_report_cip.material',
                    't_report_cip.description',
                    't_report_cip.qty',
                    't_report_cip.uom',
                    't_report_cip.amount_rp',
                    't_report_cip.amount_us',
                    't_report_cip.date',
                    't_report_cip.settle_doc',
                    't_report_cip.fa_doc'
                )
                ->where('t_master_capex.status', 1);
                return $query;


            // Proses simpan ke tabel t_report_tax menggunakan updateOrInsert
            foreach ($masterData as $data) {
                DB::table('t_report_tax')->updateOrInsert(
                    ['id_capex' => $data->id_capex], // Kunci untuk mencari record
                    [
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
                        'cap_date' => $data->cap_date,
                        'cap_doc' => $data->cap_doc,
                        'no_asset' => $data->no_asset,
                        'updated_at' => now()
                    ]
                );
            }

            DB::commit();
            return $masterData;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memproses data tax: ' . $e->getMessage());
            throw new \Exception('Gagal memproses data tax: ' . $e->getMessage());
        }
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

    public static function getAvailableYears() 
    {
        return DB::table('t_report_tax')
            ->where('status', 1)
            ->get()
            ->map(function($item) {
                preg_match('/\d{4}$/', $item->wbs_number, $matches);
                return $matches[0] ?? null;
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }
}
