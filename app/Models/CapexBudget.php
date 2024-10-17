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

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
