<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CCB extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Pastikan properti ini adalah public jika Anda menambahkannya secara eksplisit
    public $timestamps = true; // Defaultnya sudah true, jadi bisa dihapus jika tidak diubah

    protected $table = 't_cip_cum_bal';

    protected $primaryKey = 'id_ccb';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'period_cip',
        'bal_usd',
        'bal_rp',
        'cumbal_usd',
        'cumbal_rp',
        'report_status',
        'status',
        'created_by',
        'updated_by'
    ];

    public static function get_dtCipCumBal()
    {
        $sql = "SELECT 
        id_ccb, 
        period_cip, 
        FORMAT(bal_usd, 2) AS bal_usd, 
        FORMAT(bal_rp, 2) AS bal_rp, 
        FORMAT(cumbal_usd, 2) AS cumbal_usd, 
        FORMAT(cumbal_rp, 2) AS cumbal_rp, 
        report_status,
        DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') AS created_at,
        DATE_FORMAT(updated_at, '%Y-%m-%d %H:%i:%s') AS updated_at  
    FROM t_cip_cum_bal
    WHERE status = 1
    ORDER BY period_cip ASC";

        $db = DB::select($sql);

        return $db;
    }

    public static function add(
        $periodCip,
        $balUsd,
        $balRp,
        $cumbalUsd,
        $cumbalRp,
        $reportStatus = 0,
        $status = 1,
        $createdBy = null,
        $updatedBy = null
    ) {
        // Menyusun raw SQL query untuk menyimpan data
        $query = 'INSERT INTO t_cip_cum_bal (period_cip, bal_usd, bal_rp, cumbal_usd, cumbal_rp, report_status, status, created_by, updated_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        // Eksekusi query
        DB::insert($query, [
            $periodCip,
            $balUsd,
            $balRp,
            $cumbalUsd,
            $cumbalRp,
            $reportStatus,
            $status,
            $createdBy,
            $updatedBy,
            now(),
            now()
        ]);

        return ['success' => true, 'message' => 'Data Cip Cumulative Balance berhasil ditambahkan!'];
    }

    public static function updateData($id, $period_cip, $bal_usd, $bal_rp, $cumbal_usd, $cumbal_rp, $report_status, $status, $updated_by)
    {
        // Query untuk memperbarui data
        $query = 'UPDATE t_cip_cum_bal
                        SET period_cip = ?,
                            bal_usd = ?,
                            bal_rp = ?,
                            cumbal_usd = ?,
                            cumbal_rp = ?,
                            report_status = ?,
                            status = ?,
                            updated_by = ?,
                            updated_at = ?
                        WHERE id_ccb = ?';

        $params = [
            $period_cip,
            $bal_usd,
            $bal_rp,
            $cumbal_usd,
            $cumbal_rp,
            $report_status,
            $status,
            $updated_by,  // Harus sesuai dengan tipe data yang diharapkan
            now(),        // Timestamp untuk updated_at
            $id
        ];

        $result = DB::update($query, $params);

        Log::info('Update berhasil untuk ID: ' . $id);

        return ['success' => true, 'message' => 'Data berhasil diperbarui!'];
    }

    public static function getAvailableYears()
    {
        return DB::table('t_cip_cum_bal')
            ->selectRaw('DISTINCT LEFT(period_cip, 4) as year')
            ->where('status', 1)
            ->orderBy('year', 'desc')
            ->pluck('year');
    }

    public static function getPeriodRelease()
    {
        return DB::table('t_cip_cum_bal')
            ->selectRaw('period_cip, COUNT(*) as total_release')
            ->where('status', 1)
            ->where('report_status', 1)
            ->groupBy('period_cip')
            ->havingRaw('COUNT(*) > 1')
            ->get();
    }
}
