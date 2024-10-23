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
        'id_head', // Menambahkan id_head
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
            ->get();
    }

    public static function get_dtReportCip()
    {
        return  DB::table('t_report_cip')
            ->select([
                'id_report_cip',
                'id_capex',
                'id_head', // Menambahkan id_head
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

    public static function insertReportCip()
    {
        // Ambil id_capex terbaru dari t_master_capex
        $latestCapexId = DB::table('t_master_capex')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->value('id_capex');

        // Ambil data yang akan dimasukkan
        $dataToInsert = DB::table('t_faglb_tail AS f')
            ->join('t_zlis1_tail AS z', function ($join) {
                $join->on('f.asset', '=', 'z.asset')
                    ->on('f.document_number', '=', 'z.document_number_2');
            })
            ->join('t_faglb_head AS h', 'f.id_head', '=', 'h.id_head')
            ->where('f.status', 1)
            ->where('z.status', 1)
            ->select([
                'h.id_head', // Ambil id_head
                'z.document_number AS fa_doc',
                'f.posting_date AS date',
                'f.document_number AS settle_doc',
                'f.material',
                'f.text AS description',
                'f.quantity AS qty',
                'f.base_unit_of_measure AS uom',
                'f.amount_in_loc_curr_2 AS amount_rp',
                'f.amount_in_lc AS amount_us',
                DB::raw('NOW() AS created_at'),
                DB::raw('NOW() AS updated_at')
            ])
            ->get(); // Ambil data

        // Insert data hanya jika belum ada
        foreach ($dataToInsert as $data) {
            $exists = DB::table('t_report_cip')
                ->where('fa_doc', $data->fa_doc)
                ->where('date', $data->date)
                ->where('settle_doc', $data->settle_doc)
                ->exists();

            // Jika data tidak ada, maka lakukan insert
            if (!$exists) {
                DB::table('t_report_cip')->insert([
                    'id_head' => $data->id_head,
                    'id_capex' => $latestCapexId ? $latestCapexId : null, // Jika latestCapexId ada, masukkan, jika tidak, masukkan null
                    'fa_doc' => $data->fa_doc,
                    'date' => $data->date,
                    'settle_doc' => $data->settle_doc,
                    'material' => $data->material,
                    'description' => $data->description,
                    'qty' => $data->qty,
                    'uom' => $data->uom,
                    'amount_rp' => $data->amount_rp,
                    'amount_us' => $data->amount_us,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ]);
            }
        }
    }

    public static function getAvailableCapexIds()
    {
        return DB::table('t_master_capex')
            ->select('id_capex', 'project_desc') // Ambil id_capex dan deskripsi jika perlu
            ->where('status', 1)
            ->pluck('id_capex', 'project_desc'); // Menggunakan description sebagai key jika ingin menampilkan deskripsi
    }

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
