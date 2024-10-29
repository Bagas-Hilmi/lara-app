<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Attributes\Auth;
class Capex extends Model
{
    use HasFactory;
    protected $table = 't_master_capex'; // Nama tabel
    protected $primaryKey = 'id_capex'; // Atur primary key jika bukan 'id'
    public $timestamps = true; // J

    protected $fillable = [
        'project_desc',
        'wbs_capex',
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
        $query = 'INSERT INTO t_master_capex (project_desc, wbs_capex, remark, request_number, requester, capex_number, amount_budget, status_capex, budget_type, startup, expected_completed, wbs_number, cip_number, created_at, updated_at, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        DB::insert($query, [
            $projectDesc,
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

    public static function updateCapexData($id_capex, $project_desc, $wbs_capex, $remark, $request_number, $requester, $capex_number, $amount_budget, $status_capex, $budget_type, $startup, $expected_completed, $wbs_number, $cip_number,  $userId)
    {
        // Buat query untuk memperbarui data Capex
        $query = 'UPDATE t_master_capex 
                  SET project_desc = ?, 
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
            now(), // Timestamp untuk created_at
            now()  // Timestamp untuk updated_at
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
}
