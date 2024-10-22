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
            // Hitung total PO_release berdasarkan id_capex
            $totalBudgetcos = DB::table('t_capex_budget')
                ->where('id_capex', $item->id_capex)
                ->where('status', 1) // Pastikan statusnya juga 1
                ->sum('budget_cos');
            // Update PO_release di t_master_capex
            DB::table('t_master_capex')
                ->where('id_capex', $item->id_capex)
                ->update(['budget_cos' => $totalBudgetcos]);
        }
        return $query; // Mengambil semua data
    }

    public static function addBudget($capex_id, $description, $budget_cos)
    {
        // Simpan data baru ke tabel t_capex_budget menggunakan Query Builder
        $budgetId = DB::table('t_capex_budget')->insertGetId([
            'id_capex' => $capex_id,
            'description' => $description,
            'budget_cos' => $budget_cos,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Memperbarui total budget di t_master_capex
        self::get_dtCapexBudget();

        return $budgetId; // Mengembalikan ID budget yang baru ditambahkan
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

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
