<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        return DB::table('t_report_cip')
            ->join('t_master_capex', 't_report_cip.id_capex', '=', 't_master_capex.id_capex')
            ->select(
                't_report_cip.id_capex as report_id_capex',
                't_master_capex.id_capex as master_id_capex',
                't_report_cip.id_capex',
                't_master_capex.capex_number',
                't_master_capex.cip_number',
                't_master_capex.wbs_number',
                't_master_capex.project_desc',
                't_master_capex.budget_type',
                't_master_capex.amount_budget',
                't_master_capex.wbs_capex',
                't_master_capex.requester',
                't_master_capex.status_capex'

            )
            ->distinct('id_capex')
            ->where('t_report_cip.status', 1) // Anda bisa tambahkan kondisi di sini sesuai kebutuhan
            ->where('t_master_capex.status', 1) // Tambahkan kondisi status pada t_master_capex

            ->get();
    }

    public static function getEngineersForProjects()
    {
        return DB::table('t_capex_engineer')
            ->select('nama') // Mengambil kolom 'nama' saja
            ->orderBy('id_engineer', 'desc') // Mengurutkan berdasarkan 'id_engineer' terbaru
            ->first(); // Mengambil satu data terbaru
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
            ->orderBy('date', 'asc') // Urutan berdasarkan created_at, dengan ascending order
            ->get();
    }

    public static function insertReportCip()
    {
        // Ambil data yang akan dimasukkan
        // Kita mulai dari t_faglb_head yang sudah released
        $releasedHeads = DB::table('t_faglb_head')
            ->where('report_status', 1)
            ->pluck('id_head');

        if ($releasedHeads->isEmpty()) {
            Log::info('No released heads found');
            return;
        }

        $dataToInsert = DB::table('t_faglb_tail AS f')
            ->join('t_zlis1_tail AS z', function ($join) {
                $join->on('f.asset', '=', 'z.asset')
                    ->on('f.document_number', '=', 'z.document_number_2');
            })
            ->join('t_faglb_head AS h', 'f.id_head', '=', 'h.id_head')
            ->where('f.status', 1)
            ->where('z.status', 1)
            ->whereIn('f.id_head', $releasedHeads) // Hanya ambil data dari head yang released
            ->whereIn('z.id_head', $releasedHeads) // Pastikan ZLIS1 juga dari head yang released
            ->select([
                'h.id_head',
                'z.document_number AS fa_doc',
                'f.posting_date AS date',
                'f.document_number AS settle_doc',
                'f.material',
                'f.text AS description',
                'f.quantity AS qty',
                'f.base_unit_of_measure AS uom',
                'f.amount_in_loc_curr_2 AS amount_rp',
                'f.amount_in_lc AS amount_us',
                'f.asset',
                DB::raw('NOW() AS created_at'),
                DB::raw('NOW() AS updated_at')
            ])
            ->get();

        Log::info('Found ' . $dataToInsert->count() . ' records to process');

        // Proses setiap data yang ditemukan
        foreach ($dataToInsert as $data) {
            // Log nilai asset original
            Log::info('Processing asset: ' . $data->asset . ' from head ID: ' . $data->id_head);

            // Cari dengan menambahkan '-0' ke asset
            $assetWithSuffix = $data->asset . '-0';

            // Cari data dengan format yang sudah disesuaikan
            $matchingCapex = DB::table('t_master_capex')
                ->where('status', 1)
                ->where('cip_number', $assetWithSuffix)
                ->first();

            if ($matchingCapex) {
                Log::info('Found matching CIP:', [
                    'asset' => $data->asset,
                    'formatted_asset' => $assetWithSuffix,
                    'cip_number' => $matchingCapex->cip_number,
                    'id_capex' => $matchingCapex->id_capex
                ]);
            } else {
                Log::info('No match found for asset: ' . $data->asset . ' (tried: ' . $assetWithSuffix . ')');
            }

            // Cek apakah data sudah ada
            $exists = DB::table('t_report_cip')
                ->where('date', $data->date)
                ->where('settle_doc', $data->settle_doc)
                ->exists();

            // Insert jika data belum ada
            if (!$exists) {
                DB::table('t_report_cip')->insert([
                    'id_head' => $data->id_head,
                    'id_capex' => $matchingCapex ? $matchingCapex->id_capex : null,
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
                Log::info('Inserted new record for asset: ' . $data->asset);
            } else {
                Log::info('Record already exists for asset: ' . $data->asset);
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
