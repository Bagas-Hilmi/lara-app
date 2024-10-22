<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    use HasFactory;
    protected $table = 't_report_cip';
    protected $primaryKey = 'id_report_cip';
    public $timestamps = true;

    protected $fillable = [
        'id_capex',
        'fa_doc',
        'date',
        'settle_doc',
        'material',
        'description',
        'qty',
        'uom',
        'amount_rp',
        'amount_us',

    ];

    public static function getActiveCapexDescriptions()
    {
        return Capex::select('id_capex', 'project_desc', 'cip_number', 'wbs_number', 'capex_number', 'budget_type', 'amount_budget')
            ->where('status', 1)
            ->whereNotNull('cip_number') // Hanya mengambil data yang tidak null pada cip_number
            ->whereNotNull('wbs_number')
            ->get();
    }

    public static function get_dtReportCip()
    {
        return  DB::table('t_report_cip')
            ->select([
                'id_report_cip',
                'id_capex',
                'fa_doc',
                'date',
                'settle_doc',
                'material',
                'description',
                'qty',
                'uom',
                'amount_rp',
                'amount_us',
                'created_at',
                'updated_at'
            ])
            ->orderBy('created_at', 'asc') // Urutan berdasarkan created_at, dengan ascending order
            ->get();
    }

    public static function populateReportFromFaglbTail()
    {
        return DB::table('t_faglb_tail')
            ->join('t_zlis1_tail', function ($join) {
                $join->on('t_faglb_tail.asset', '=', 't_zlis1_tail.asset') // Syarat 1: Kolom asset harus sama
                     ->whereColumn('t_faglb_tail.document_number', 't_zlis1_tail.document_number_2'); // Syarat 2: Kolom document_number harus sama
            })
            ->select([
                't_zlis1_tail.document_number as fa_doc', // Mengambil fa_doc dari t_zlis1_tail
                't_faglb_tail.posting_date as date',
                't_faglb_tail.document_number as settle_doc',
                't_faglb_tail.material',
                't_faglb_tail.text as description',
                't_faglb_tail.quantity as qty',
                't_faglb_tail.base_unit_of_measure as uom',
                't_faglb_tail.amount_in_loc_curr_2 as amount_rp',
                't_faglb_tail.amount_in_lc as amount_us'
            ])
            ->get()
            ->each(function ($item) {
                // Menyimpan setiap item ke dalam tabel t_report_cip
                DB::table('t_report_cip')->insert([
                    'fa_doc' => $item->fa_doc,
                    'date' => $item->date,
                    'settle_doc' => $item->settle_doc,
                    'material' => $item->material,
                    'description' => $item->description,
                    'qty' => $item->qty,
                    'uom' => $item->uom,
                    'amount_rp' => $item->amount_rp,
                    'amount_us' => $item->amount_us,
                    'created_at' => now(), // Menambahkan timestamp
                    'updated_at' => now(),
                ]);
            });
    }

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
