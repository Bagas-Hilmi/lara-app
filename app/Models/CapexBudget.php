<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CapexBudget extends Model
{
    use HasFactory;
    protected $table = 't_capex_budget';
    protected $primaryKey = 'id_capex_budget';
    public $timestamps = true; // J

    protected $fillable = [
        'id_capex',
        'description',
        'budget',

    ];

    public static function get_dtCapexBudget()
    {
        // Ambil data dari tabel t_capex_budget dengan status = 1
        $query = DB::select("
        SELECT 
            id_capex_budget, 
            id_capex, 
            description, 
            budget_cos, 
            created_at, 
            updated_at 
        FROM t_capex_budget
        WHERE status = 1
    ");

        // Iterasi hasil query untuk menghitung total budget_cos
        foreach ($query as $item) {
            // Hitung total budget_cos untuk id_capex tertentu
            $totalBudgetcos = DB::selectOne("
            SELECT SUM(budget_cos) AS total_budget_cos
            FROM t_capex_budget
            WHERE id_capex = ? AND status = 1
        ", [$item->id_capex]);

            // Update tabel t_master_capex dengan total_budget_cos
            DB::update("
            UPDATE t_master_capex
            SET budget_cos = ?
            WHERE id_capex = ?
        ", [$totalBudgetcos->total_budget_cos, $item->id_capex]);
        }

        return $query; // Mengembalikan hasil data
    }


    public static function addBudget($capex_id, $description, $budget_cos)
    {
        // Menyusun query SQL untuk insert data
        $query = "
        INSERT INTO t_capex_budget (id_capex, description, budget_cos, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?)
    ";

        // Eksekusi query dan dapatkan ID yang dihasilkan
        DB::insert($query, [
            $capex_id,
            $description,
            $budget_cos,
            now(),
            now(),
        ]);

        // Ambil ID terakhir yang dimasukkan
        $budgetId = DB::getPdo()->lastInsertId();

        // Memanggil fungsi untuk memperbarui total budget
        self::get_dtCapexBudget();

        // Mengembalikan ID budget yang baru ditambahkan
        return $budgetId;
    }


    public static function updateBudget($id, $description, $budget_cos, $capex_id)
    {
        // Perbarui data budget berdasarkan ID menggunakan Query Builder
        $result = DB::table('t_capex_budget')
            ->where('id_capex_budget', $id) // Gantilah 'id_capex_budget' sesuai dengan nama kolom ID di tabel Anda
            ->update([
                'description' => $description,
                'budget_cos' => $budget_cos,
                'id_capex' => $capex_id,
                'updated_at' => now(), // Timestamp untuk updated_at
            ]);

        // Memperbarui total budget di t_master_capex
        self::get_dtCapexBudget();

        return $result; // Mengembalikan hasil update
    }

    public static function getStatus($id, $status = null)
    {
        $query = DB::table('t_capex_budget')
            ->join('t_master_capex', 't_capex_budget.id_capex', '=', 't_master_capex.id_capex')
            ->select('t_capex_budget.*', 't_master_capex.status_capex');

        if ($id) {
            $query->where('t_capex_budget.id_capex', $id); // Filter berdasarkan ID
        }

        if (!is_null($status)) {
            $query->where('t_capex_budget.status', $status); // Tambahkan filter status jika diberikan
        }

        return $query->get();
    }


    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
