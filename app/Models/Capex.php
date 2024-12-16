<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Attributes\Auth;
use Carbon\Carbon; 

class Capex extends Model
{
    use HasFactory;
    protected $table = 't_master_capex'; // Nama tabel
    protected $primaryKey = 'id_capex'; // Atur primary key jika bukan 'id'
    public $timestamps = true; 

    protected $fillable = [
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
        'PO_release',
        'status_capex',
        'budget_type',
        'startup',
        'expected_completed',
        'wbs_number',
        'cip_number',
        'status',
        'created_by',
        'updated_by'
    ];
    public static function get_dtCapex()
    {
        $query = DB::table('t_master_capex')
            ->select([
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
                'status_capex',
                'budget_type',
                'startup',
                'expected_completed',
                'wbs_number',
                'cip_number',
                'created_at',
                'updated_at'
            ])
            ->orderBy('asc'); // 

        return $query->get(); // Mengambil semua data
    }

    public static function add(
        $projectDesc,
        $category,
        $wbsCapex,
        $remark,
        $requestNumber,
        $requester,
        $capexNumber,
        $amountBudget,
        $statusCapex,
        $budgetType,
        $startup,
        $expectedCompleted,
        $wbsNumber,
        $cipNumber,
        $userId // Tambahkan userId sebagai parameter
    ) {

        // Simpan data baru ke database dengan raw SQL
        $query = 'INSERT INTO t_master_capex (project_desc, category, wbs_capex, remark, request_number, requester, capex_number, amount_budget, status_capex, budget_type, startup, expected_completed, wbs_number, cip_number, created_at, updated_at, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        DB::insert($query, [
            $projectDesc,
            $category,
            $wbsCapex,
            $remark,
            $requestNumber,
            $requester,
            $capexNumber,
            $amountBudget,
            $statusCapex,
            $budgetType,
            $startup,
            $expectedCompleted,
            $wbsNumber,
            $cipNumber,
            now(),
            now(),
            $userId, // created_by
        ]);

        // Mendapatkan ID capex terakhir yang dimasukkan
        $lastInsertedId = DB::getPdo()->lastInsertId();

        // Simpan data ke tabel CapexStatus (asumsi id_capex adalah foreign key)
        DB::insert('INSERT INTO t_capex_status (id_capex, status, created_at, updated_at, created_by) VALUES (?, ?, ?, ?, ?)', [
            $lastInsertedId,
            $statusCapex,
            now(),
            now(),
            $userId, // created_by
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Capex berhasil ditambahkan!'
        ]);
    }

    public static function updateCapexData($id_capex, $project_desc, $category, $wbs_capex, $remark, $request_number, $requester, $capex_number, $amount_budget, $status_capex, $budget_type, $startup, $expected_completed, $wbs_number, $cip_number,  $userId)
    {
        // Buat query untuk memperbarui data Capex
        $query = 'UPDATE t_master_capex 
                    SET project_desc = ?, 
                        category = ?, 
                        wbs_capex = ?, 
                        remark = ?, 
                        request_number = ?, 
                        requester = ?, 
                        capex_number = ?, 
                        amount_budget = ?, 
                        status_capex = ?, 
                        budget_type = ?, 
                        startup = ?, 
                        expected_completed = ?, 
                        wbs_number = ?, 
                        cip_number = ?, 
                        updated_at = ?,
                        updated_by = ?
                    WHERE id_capex = ?';

        // Buat parameter untuk query
        $params = [
            $project_desc,
            $category,
            $wbs_capex,
            $remark,
            $request_number,
            $requester,
            $capex_number,
            $amount_budget,
            $status_capex,
            $budget_type,
            $startup,
            $expected_completed,
            $wbs_number,
            $cip_number,
            now(), // Timestamp untuk updated_at
            $userId,
            $id_capex
        ];

        // Eksekusi query
        $result = DB::update($query, $params);

        // Tambahkan status capex ke tabel CapexStatus
        $statusQuery = 'INSERT INTO capex_status (id_capex, status, created_by, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?)';
        $statusParams = [
            $id_capex,
            $status_capex,
            $userId,
            now(), 
            now()  
        ];

        DB::insert($statusQuery, $statusParams);


        return ['success' => true, 'message' => 'Data capex berhasil diperbarui!'];
    }

    public static function getAvailableYears()
    {
        return DB::table('t_master_capex')
            ->selectRaw('DISTINCT LEFT(created_at, 4) as year')
            ->where('status', 1)
            ->orderBy('year', 'desc')
            ->pluck('year');
    }

    public static function getTotalBudget()
    {
        $affectedRows = DB::table('t_master_capex')
            ->where('status', 1)
            ->update([
                'total_budget' => DB::raw('COALESCE(amount_budget, 0) + COALESCE(budget_cos, 0)')
            ]);

        return response()->json(['message' => "{$affectedRows} rows updated."]);
    }

    public static function getDaysLate()
    {
        // Ambil semua record dari t_master_capex
        $capexRecords = DB::table('t_master_capex')->get();

        foreach ($capexRecords as $record) {
            // Menghitung selisih hari dari tanggal sekarang hingga tanggal expected_completed
            $endDate = Carbon::parse($record->expected_completed);
            $daysLeft = Carbon::now()->diffInDays($endDate); // Hitung selisih hari

            // Jika sudah lewat, simpan nilai hari yang sudah lewat ke kolom days_late
            if ($daysLeft < 0) {
                // Menghitung berapa banyak hari sudah lewat
                $daysOverdue = abs($daysLeft); // Ambil nilai absolut dari daysLeft
                // Update kolom days_late dengan hari yang sudah lewat
                DB::table('t_master_capex')
                    ->where('id_capex', $record->id_capex)
                    ->update(['days_late' => $daysOverdue]);
            } else {
                // Jika belum lewat, set days_late ke 0
                DB::table('t_master_capex')
                    ->where('id_capex', $record->id_capex)
                    ->update(['days_late' => null]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Days late calculated and updated successfully!']);
    }

    public static function getDaysRemaining()
    {
        // Ambil semua record dari t_master_capex
        $capexRecords = DB::table('t_master_capex')->get();

        foreach ($capexRecords as $record) {
            // Menghitung selisih hari dari tanggal sekarang hingga tanggal expected_completed
            $endDate = Carbon::parse($record->expected_completed);
            $daysRemaining = Carbon::now()->diffInDays($endDate); // Hitung selisih hari

            // Jika sudah lewat, set days_remaining ke 0
            if ($daysRemaining < 0) {
                $daysRemaining = null; // Set ke 0 jika sudah lewat
            }

            // Update kolom days_remaining
            DB::table('t_master_capex')
                ->where('id_capex', $record->id_capex)
                ->update(['days_remaining' => $daysRemaining]);
        }

        return response()->json(['success' => true, 'message' => 'Days remaining calculated and updated successfully!']);
    }

    public function CapexBudget()
    {
        return $this->hasMany(CapexBudget::class, 'id_capex');
    }

    public function CapexProgress()
    {
        return $this->hasMany(CapexProgress::class, 'id_capex');
    }

    public function CapexCompletion()
    {
        return $this->hasMany(CapexCompletion::class, 'id_capex');
    }

    public function CapexPOrelease()
    {
        return $this->hasMany(CapexPOrelease::class, 'id_capex');
    }

    public function CapexStatus()
    {
        return $this->hasMany(CapexStatus::class, 'id_capex');
    }
    public function CapexPOcommitment()
    {
        return $this->hasMany(CapexPOcommitment::class, 'id_capex');
    }

    public function CapexEngineer()
    {
        return $this->hasMany(CapexEngineer::class,'id_capex');
    }

    public function Report()
    {
        return $this->hasMany(Report::class,'id_capex');
    }

    public function ReportCategory()
    {
        return $this->hasMany(ReportCategory::class,'id_capex');
    }
    
    public function ReportSummary()
    {
        return $this->hasMany(ReportSummary::class,'id_capex');
    }
}
