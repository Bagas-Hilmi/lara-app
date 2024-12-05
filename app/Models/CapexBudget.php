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
        $query = DB::table('t_capex_budget')
            ->select([
                'id_capex_budget',
                'id_capex',
                'description',
                'budget_cos',
                'created_at',
                'updated_at'
            ])
            ->where('status', 1) // Tambahkan kondisi untuk status
            ->get();

        foreach ($query as $item) {

            $totalBudgetcos = DB::table('t_capex_budget')
                ->where('id_capex', $item->id_capex)
                ->where('status', 1) // Pastikan statusnya juga 1
                ->sum('budget_cos');


            DB::table('t_master_capex')
                ->where('id_capex', $item->id_capex)
                ->update(['budget_cos' => $totalBudgetcos]);
        }
        return $query; // Mengambil semua data
    }

    public static function addBudget($capex_id, $description, $budget_cos)
    {
        $budgetId = DB::table('t_capex_budget')->insertGetId([
            'id_capex' => $capex_id,
            'description' => $description,
            'budget_cos' => $budget_cos,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
        self::get_dtCapexBudget();

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
